<?= $this->extend('layout/index') ?>

<?= $this->section('content') ?>
<!-- products-breadcrumb -->
	<div class="products-breadcrumb">
		<div class="container">
			<ul>
				<li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo site_url('home') ?>"><?php echo lang('STD.std_home') ?></a><span>|</span></li>
				<li><?php echo lang('STD.std_checkout') ?></li>
			</ul>
		</div>
	</div>
<!-- //products-breadcrumb -->
<!-- banner -->
	<div class="banner">
		<?= $this->include('layout/side-menu') ?>

		<div class="w3l_banner_nav_right">
<!-- about -->
		<div class="privacy about">
			<h3><?php echo lang('STD.std_checkout') ?></h3>

			<?php 
				$product_list = array(
					array(
						'img_url' => site_url('assets/img/1.png'),
						'name' => 'Lorem ipsum dolor',
						'amount' => rand(0, 100000),
						'discount_amount' => rand(20, 500000),
						'qte' => rand(0, 20),
						'currency_code' => 'USD',
					),
					array(
						'img_url' => site_url('assets/img/2.png'),
						'name' => 'Lorem ipsum dolor',
						'amount' => rand(0, 100000),
						'discount_amount' => rand(20, 500000),
						'qte' => rand(0, 20),
						'currency_code' => 'USD',
					),
					array(
						'img_url' => site_url('assets/img/3.png'),
						'name' => 'Lorem ipsum dolor',
						'amount' => rand(0, 100000),
						'discount_amount' => rand(20, 500000),
						'qte' => rand(0, 20),
						'currency_code' => 'USD',
					),
					array(
						'img_url' => site_url('assets/img/4.png'),
						'name' => 'Lorem ipsum dolor',
						'amount' => rand(0, 100000),
						'discount_amount' => rand(20, 500000),
						'qte' => rand(0, 20),
						'currency_code' => 'USD',
					),
				);
			 ?>
			
	      	<div class="checkout-right">
				<h4><?php echo lang('STD.std_your_shopping_cart_contain') ?> : <span>3 <?php echo lang('STD.std_product') ?></span></h4>
				<table class="timetable_sub">
					<thead>
						<tr>
							<th><?php echo lang('STD.std_sl_no') ?></th>	
							<th><?php echo lang('STD.std_product') ?></th>
							<th><?php echo lang('STD.std_quantity') ?></th>
							<th><?php echo lang('STD.std_product_name') ?></th>
						
							<th><?php echo lang('STD.std_price') ?></th>
							<th><?php echo lang('STD.std_remove') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($product_list as $index => $product) {
								?>
						<tr class="rem1">
							<td class="invert"><?php echo $index + 1; ?></td>
							<td class="invert-image"><a href="single.html"><img src="<?php echo $product['img_url']; ?>" alt=" " class="img-responsive"></a></td>
							<td class="invert">
								 <div class="quantity"> 
									<div class="quantity-select">                           
										<div class="entry value-minus">&nbsp;</div>
										<div class="entry value"><span><?php echo $product['qte']; ?></span></div>
										<div class="entry value-plus active">&nbsp;</div>
									</div>
								</div>
							</td>
							<td class="invert"><?php echo $product['name']; ?></td>
							
							<td class="invert"><?php echo $product['amount']; ?> Ar</td>
							<td class="invert">
								<button class="btn btn-default"><i class="fa fa-times"></i></button>
							</td>
						</tr>
								<?php
							}
						 ?>
					</tbody>
				</table>
			</div>
			<div class="checkout-left">	
				<div class="col-md-4 checkout-left-basket">
					<h4><?php echo lang('STD.std_continue_to_basket') ?></h4>
					<ul>
						<?php 
							$total = 0;
							foreach ($product_list as $index => $product) {
								$price = $product['qte'] * $product['amount'];
								$total += $price;
								?>
						<li><?php echo $product['name']; ?> <i>-</i> <span><?php echo $price; ?> Ar</span></li>
								<?php
							}
							$taxes = $total / 0.8;
							$total_ttc = $total + $taxes;
						 ?>
						<li class="active"><?php echo lang('STD.std_total') ?> <i>-</i> <span><?php echo $total; ?> Ar</span></li>
						<li><?php echo lang('STD.std_taxes') ?> <i>-</i> <span><?php echo $taxes; ?> Ar</span></li>
						<li class="active"><?php echo lang('STD.std_total_ttc') ?> <i>-</i> <span><?php echo $total_ttc; ?> Ar</span></li>
					</ul>
				</div>
				<div class="col-md-8 address_form_agile">
					  <h4>Add a new Details</h4>
				<form action="payment.html" method="post" class="creditly-card-form agileinfo_form">
									<section class="creditly-wrapper wthree, w3_agileits_wrapper">
										<div class="information-wrapper">
											<div class="first-row form-group">
												<div class="controls">
													<label class="control-label"><?php echo lang('STD.std_name') ?>: </label>
													<input class="billing-address-name form-control" type="text" name="name" placeholder="<?php echo lang('STD.std_full_name_please') ?>">
												</div>
												<div class="w3_agileits_card_number_grids">
													<div class="w3_agileits_card_number_grid_left">
														<div class="controls">
															<label class="control-label"><?php echo lang('STD.std_phone') ?>:</label>
														    <input class="form-control" type="text" placeholder="<?php echo lang('STD.std_phone_number') ?>">
														</div>
													</div>
													<div class="clear"> </div>
												</div>
												<div class="controls">
													<label class="control-label"><?php echo lang('STD.std_adress') ?>: </label>
												 <input class="form-control" type="text" placeholder="<?php echo lang('STD.std_adress') ?>">
											</div>
											<!-- <button class="submit check_out">Delivery to this Address</button> -->
										</div>
									</section>
								</form>
									<div class="checkout-right-basket">
				        	<a href="<?php echo site_url('home/payement') ?>"><?php echo lang('STD.std_make_payement') ?> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
			      	</div>
					</div>
			
				<div class="clearfix"> </div>
				
			</div>

		</div>
<!-- //about -->
		</div>
		<div class="clearfix"></div>
	</div>
<!-- //banner -->
<?= $this->endSection() ?>


