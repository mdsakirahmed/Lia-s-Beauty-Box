<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Invoice - {{ config('app.name') }}</title>
        @page {
            header: page-header;
            footer: page-footer;
        }
		<style>
			.invoice-box {
				max-width: 100%;
				margin: auto;
				padding: 30px;
				/* border: 1px solid #eee; */
				/* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<img src="{{ asset(get_static_option('logo') ?? 'assets/frontend/images/logo.png') }}" style="width: 100%; max-width: 300px" />
								</td>

								<td>
									Invoice #: {{ $invoice->id }}<br /><br />
									Date: {{ $invoice->created_at->format('d/m/Y') }}<br />
									Time: {{ $invoice->created_at->format('h:i A') }}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									{{ $invoice->appointment->customer->name ?? '#' }}<br />
									{{ $invoice->appointment->customer->email ?? '#' }}<br />
									{{ $invoice->appointment->customer->phone ?? '#' }}
								</td>

								<td>
									{{ config('app.name') }}<br />
									{{ get_static_option('email') ?? 'email@example.com' }}<br />
									{{ get_static_option('address') ?? 'Example Address' }}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="heading">
					<td>Service</td>
					<td>Price</td>
				</tr>
                @foreach ($invoice->items as $item)
                <tr class="item @if($loop->last) last @endif">
                    <td>{{ $item->service->name }}</td>
                    <td>BDT {{ $item->price }} x {{ $item->quantity }} = BDT {{ $item->price * $item->quantity }}</td>
                </tr>
                @endforeach
				<tr class="total">
					<td></td>
					<td>
                        Total:
                        @if(inv_calculator($invoice)['discount_amount'] > 0)
                        <del><small>BDT {{ inv_calculator($invoice)['main_price'] }}</small></del><sup>{{  inv_calculator($invoice)['discount_percentage'] }}%OFF</sup>
                        BDT {{ inv_calculator($invoice)['price_after_discount'] }}
                        @else
                        BDT {{ inv_calculator($invoice)['main_price'] }}
                        @endif
                    </td>
				</tr>

				<tr class="total">
					<td></td>
					<td>Vat({{ inv_calculator($invoice)['vat_percentage'] }}%): BDT {{ inv_calculator($invoice)['vat_amount'] }}</td>
				</tr>
				<tr class="total">
					<td></td>
					<td>Grand Total: BDT {{ inv_calculator($invoice)['price'] }}</td>
				</tr>
				<tr class="total">
					<td></td>
					<td>Paid: BDT {{ inv_calculator($invoice)['paid'] }}</td>
				</tr>
				{{-- <tr class="total">
					<td></td>
					<td style="color:red;">DUE: BDT {{ inv_calculator($invoice)['due'] }}</td>
				</tr> --}}
			</table>
            <table cellpadding="0" cellspacing="0" style="margin-top: 60px;">
                <tr>
                    <th>
                        Developed By <a href="https://www.iciclecorporation.com/" target="_blank">Icicle Corporation</a>
                    </th>
                </tr>
            </table>
		</div>
	</body>
</html>
