<?php

namespace Whilesmart\Customers\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Whilesmart\Customers\Http\Requests\StoreCustomerRequest;
use Whilesmart\Customers\Http\Requests\UpdateCustomerRequest;
use Whilesmart\Customers\Http\Resources\CustomerResource;
use Whilesmart\Customers\Models\Customer;
use Whilesmart\OwnerAccess\Concerns\AuthorizesOwnerController;

class CustomerController extends Controller
{
    use AuthorizesOwnerController;

    public function index(Request $request): JsonResponse
    {
        $query = $this->scopeAccessibleOwners(Customer::query(), $request->user());

        if ($request->filled('owner_type') && $request->filled('owner_id')) {
            $query->where('owner_type', $request->input('owner_type'))
                  ->where('owner_id', $request->input('owner_id'));
        }

        if ($request->filled('q')) {
            $term = '%'.strtolower($request->input('q')).'%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('lower(name) like ?', [$term])
                  ->orWhereRaw('lower(email) like ?', [$term])
                  ->orWhereRaw('lower(company_name) like ?', [$term]);
            });
        }

        $customers = $query->orderByDesc('updated_at')
            ->paginate((int) $request->input('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => CustomerResource::collection($customers)->response()->getData(true),
        ]);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
        ], 201);
    }

    public function show(Request $request, Customer $customer): JsonResponse
    {
        $this->authorizeAccessTo($customer, $request->user());

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $this->authorizeAccessTo($customer, $request->user());
        $customer->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
        ]);
    }

    public function destroy(Request $request, Customer $customer): JsonResponse
    {
        $this->authorizeAccessTo($customer, $request->user());
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted.',
        ]);
    }
}
