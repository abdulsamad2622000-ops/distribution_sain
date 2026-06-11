<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{GoodsReceivedNote, GrnItem, PurchaseOrder, PurchaseOrderItem, Product, Supplier};

class GoodsReceivedNoteController extends Controller
{
    public function index(Request $request)
    {
        $grns = GoodsReceivedNote::with('supplier', 'purchaseOrder', 'user')
            ->when($request->supplier_id, fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->from, fn($q) => $q->whereDate('received_date', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->whereDate('received_date', '<=', $request->to))
            ->latest()->paginate(20);

        $suppliers = Supplier::orderBy('name')->get();
        return view('grns.index', compact('grns', 'suppliers'));
    }

    public function create(Request $request)
    {
        $po = null;
        if ($request->po_id) {
            $po = PurchaseOrder::with('supplier', 'items.product')->findOrFail($request->po_id);
        }
        $approved_pos = PurchaseOrder::where('status', 'approved')
            ->orWhere('status', 'partial')
            ->with('supplier')
            ->latest()
            ->get();
        $grn_number = GoodsReceivedNote::generateGrnNumber();
        return view('grns.create', compact('po', 'approved_pos', 'grn_number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id'    => 'required|exists:purchase_orders,id',
            'received_date'        => 'required|date',
            'items'                => 'required|array|min:1',
            'items.*.po_item_id'   => 'required|exists:purchase_order_items,id',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.qty_received' => 'required|numeric|min:0.01',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $po = PurchaseOrder::findOrFail($request->purchase_order_id);

            $grn = GoodsReceivedNote::create([
                'grn_number'        => GoodsReceivedNote::generateGrnNumber(),
                'purchase_order_id' => $po->id,
                'supplier_id'       => $po->supplier_id,
                'user_id'           => auth()->id(),
                'received_date'     => $request->received_date,
                'notes'             => $request->notes,
                'status'            => 'completed',
            ]);

            foreach ($request->items as $item) {
                if ($item['qty_received'] <= 0) continue;

                $line_total = $item['qty_received'] * $item['unit_price'];

                GrnItem::create([
                    'goods_received_note_id' => $grn->id,
                    'purchase_order_item_id' => $item['po_item_id'],
                    'product_id'             => $item['product_id'],
                    'qty_received'           => $item['qty_received'],
                    'unit_price'             => $item['unit_price'],
                    'total_price'            => $line_total,
                ]);

                // Stock increase
                Product::find($item['product_id'])?->increment('stock_qty', $item['qty_received']);

                // Update PO item received qty
                $po_item = PurchaseOrderItem::find($item['po_item_id']);
                if ($po_item) {
                    $po_item->increment('received_qty', $item['qty_received']);
                }
            }

            // Update PO status
            $po->refresh();
            $all_received = $po->items->every(fn($i) => $i->received_qty >= $i->qty);
            $any_received = $po->items->some(fn($i)  => $i->received_qty > 0);

            $po->update([
                'status' => $all_received ? 'received' : ($any_received ? 'partial' : 'approved')
            ]);
        });

        return redirect()->route('grns.index')
            ->with('success', 'GRN created and stock updated successfully!');
    }

    public function show(GoodsReceivedNote $grn)
    {
        $grn->load('supplier', 'purchaseOrder', 'user', 'items.product');
        return view('grns.show', compact('grn'));
    }
}