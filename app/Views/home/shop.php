<?= $this->extend('layout/index') ?>

<?= $this->section('content') ?>

<!-- products-breadcrumb -->
	<div class="products-breadcrumb">
		<div class="container">
			<ul>
				<li><i class="fa fa-home" aria-hidden="true"></i><a href="index.html">Home</a><span>|</span></li>
				<li>Households</li>
			</ul>
		</div>
	</div>
<!-- //products-breadcrumb -->
<!-- banner -->
	<div class="banner">
		<?= $this->include('layout/side-menu') ?>

		<div class="w3l_banner_nav_right">
			<div class="w3l_banner_nav_right_banner4" style="background-image: url('<?php echo site_url('assets/images/shop-cover-1.jpeg') ?>');">
				
			</div>
			<div class="w3ls_w3l_banner_nav_right_grid w3ls_w3l_banner_nav_right_grid_sub">
				<h3><?php echo lang('STD.std_product') ?></h3>
				<div class="w3ls_w3l_banner_nav_right_grid1">
					<h6><?php echo lang('STD.std_medecine') ?></h6>

					<div class="row">
						<?php 
							foreach ($product_list as $product) {
								?>
						<div class="col-md-3 top_brand_left" style="margin-top: 30px;">
							<div class="hover14 column">
								<div class="agile_top_brand_left_grid w3l_agile_top_brand_left_grid">
									<div class="agile_top_brand_left_grid_pos">
										<img src="images/offer.png" alt=" " class="img-responsive" />
									</div>
									<div class="agile_top_brand_left_grid1">
										<figure>
											<div class="snipcart-item block" >
												<div class="snipcart-thumb">
													<a href="single.html"><img title=" " alt=" " src="<?php echo site_url('image/' . $product['image_id']) ; ?>" /></a>		
													<p><?php echo $product['name']; ?></p>
													<h4><?php echo $product['price']; ?> Ar <span><?php echo $product['price_before']; ?> Ar</span></h4>
												</div>
												<div class="snipcart-details">
													<form action="<?php echo site_url('home/checkout') ?>" method="post">
														<fieldset>
															<input type="hidden" name="cmd" value="_cart" />
															<input type="hidden" name="add" value="1" />
															<input type="hidden" name="business" value=" " />
															<input type="hidden" name="item_name" value="<?php echo $product['name']; ?>" />
															<input type="hidden" name="amount" value="<?php echo $product['price']; ?>" />
															<input type="hidden" name="discount_amount" value="<?php echo $product['price_before']; ?>" />
															<input type="hidden" name="currency_code" value="USD" />
															<input type="hidden" name="return" value=" " />
															<input type="hidden" name="cancel_return" value=" " />
															<input type="submit" name="submit" value="Add to cart" class="button" />
														</fieldset>
															
													</form>
											
												</div>
											</div>
										</figure>
									</div>
								</div>
							</div>
						</div>
								<?php
							}
						 ?>
						<div class="clearfix"> </div>
					</div>
					

					<div class="row">
						<div class="col-12">
							<div class="" style="margin-top: 30px;">
								<?= $pager->makeLinks($page, $limit, $total, 'simple_pager', 0, 'shop') ?>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
<!-- //banner -->
<?= $this->endSection() ?>