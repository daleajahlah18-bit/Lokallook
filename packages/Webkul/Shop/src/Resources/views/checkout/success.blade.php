<x-shop::layouts
	:has-header="true"
	:has-feature="false"
	:has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
		@lang('shop::app.checkout.success.thanks')
    </x-slot>

	<!-- Page content -->
	<div class="container mt-8 px-[60px] max-lg:px-8">
		<div class="grid place-items-center gap-y-5 max-md:gap-y-2.5">
			{{ view_render_event('bagisto.shop.checkout.success.image.before', ['order' => $order]) }}

			<img
				class="max-md:h-[100px] max-md:w-[100px]"
				src="{{ bagisto_asset('images/thank-you.png') }}"
				alt="@lang('shop::app.checkout.success.thanks')"
				title="@lang('shop::app.checkout.success.thanks')"
                loading="lazy"
                decoding="async"
			>

			{{ view_render_event('bagisto.shop.checkout.success.image.after', ['order' => $order]) }}

			<p class="text-xl max-md:text-sm">
				@if (auth()->guard('customer')->user())
					@lang('shop::app.checkout.success.order-id-info', [
						'order_id' => '<a class="text-blue-700" href="'.route('shop.customers.account.orders.view', $order->id).'">'.$order->increment_id.'</a>'
					])
				@else
					@lang('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id])
				@endif
			</p>

			<p class="font-medium md:text-2xl">
				@lang('shop::app.checkout.success.thanks')
			</p>

			<p class="text-xl text-zinc-500 max-md:text-center max-md:text-xs">
				@if (! empty($order->checkout_message))
					{!! nl2br($order->checkout_message) !!}
				@else
					@lang('shop::app.checkout.success.info')
				@endif
			</p>

			<!-- Payment Receipt -->
			<div class="mt-8 w-full max-w-2xl bg-white border border-gray-200 rounded-lg shadow-md p-6 max-md:p-4">
				<h2 class="text-2xl font-bold text-center mb-6">@lang('Struk Pembayaran')</h2>

				<div class="grid grid-cols-2 gap-4 mb-6">
					<div>
						<strong>@lang('Nama Penerima'):</strong> {{ $order->customer_full_name }}
					</div>
					<div>
						<strong>@lang('ID Transaksi'):</strong> {{ $order->increment_id }}
					</div>
					<div>
						<strong>@lang('Tanggal Transaksi'):</strong> {{ $order->created_at->format('d M Y H:i') }}
					</div>
					<div>
						<strong>@lang('Metode Pembayaran'):</strong> {{ $order->payment->method ?? 'N/A' }}
					</div>
				</div>

				<table class="w-full border-collapse border border-gray-300 mb-6">
					<thead>
						<tr class="bg-gray-100">
							<th class="border border-gray-300 px-4 py-2 text-left">@lang('Nama Item')</th>
							<th class="border border-gray-300 px-4 py-2 text-center">@lang('Jumlah Barang')</th>
							<th class="border border-gray-300 px-4 py-2 text-right">@lang('Harga Barang')</th>
							<th class="border border-gray-300 px-4 py-2 text-right">@lang('Harga Tuntas')</th>
						</tr>
					</thead>
					<tbody>
						@foreach($order->items as $item)
						<tr>
							<td class="border border-gray-300 px-4 py-2">{{ $item->name }}</td>
							<td class="border border-gray-300 px-4 py-2 text-center">{{ $item->qty_ordered }}</td>
							<td class="border border-gray-300 px-4 py-2 text-right">{{ core()->formatPrice($item->price) }}</td>
							<td class="border border-gray-300 px-4 py-2 text-right">{{ core()->formatPrice($item->total) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				<div class="text-right">
					<strong class="text-xl">@lang('Total Pembayaran'): {{ core()->formatPrice($order->grand_total) }}</strong>
				</div>
			</div>

			{{ view_render_event('bagisto.shop.checkout.success.continue-shopping.before', ['order' => $order]) }}

			<a href="{{ route('shop.home.index') }}">
				<div class="w-max cursor-pointer rounded-2xl bg-navyBlue px-11 py-3 text-center text-base font-medium text-white max-md:rounded-lg max-md:px-6 max-md:py-1.5">
             		@lang('shop::app.checkout.cart.index.continue-shopping')
				</div>
			</a>

			{{ view_render_event('bagisto.shop.checkout.success.continue-shopping.after', ['order' => $order]) }}
		</div>
	</div>
</x-shop::layouts>
