<?= $this->extend('layout/index') ?>

<?= $this->section('content') ?>

<!-- banner -->
	<div class="banner">
		<?= $this->include('layout/side-menu') ?>
		<div class="w3l_banner_nav_right">
			<section class="slider">
				<div class="flexslider">
					<ul class="slides">
						<li>
							<div class="w3l_banner_nav_right_banner" style="background-image: url('<?php echo site_url('assets/images/drone-1.jpg') ?>');">
								<h3>Make your <span>food</span> with Spicy.</h3>
								<div class="more">
									<a href="products.html" class="button--saqui button--round-l button--text-thick" data-text="Shop now">Shop now</a>
								</div>
							</div>
						</li>
						<li>
							<div class="w3l_banner_nav_right_banner1" style="background-image: url('<?php echo site_url('assets/images/drone-2.jpg') ?>');">
								<h3>Make your <span>food</span> with Spicy.</h3>
								<div class="more">
									<a href="products.html" class="button--saqui button--round-l button--text-thick" data-text="Shop now">Shop now</a>
								</div>
							</div>
						</li>
						<li>
							<div class="w3l_banner_nav_right_banner2" style="background-image: url('<?php echo site_url('assets/images/drone-3.jpg') ?>');">
								<h3>upto <i>50%</i> off.</h3>
								<div class="more">
									<a href="products.html" class="button--saqui button--round-l button--text-thick" data-text="Shop now">Shop now</a>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</section>
			<!-- flexSlider -->
				<link rel="stylesheet" href="<?php echo site_url('assets/css/flexslider.css') ?>" type="text/css" media="screen" property="" />
				<script defer src="<?php echo site_url('assets/js/jquery.flexslider.js') ?>"></script>
				<script type="text/javascript">
				$(window).load(function(){
				  $('.flexslider').flexslider({
					animation: "slide",
					start: function(slider){
					  $('body').removeClass('loading');
					}
				  });
				});
			  </script>
			<!-- //flexSlider -->
		</div>
		<div class="clearfix"></div>
	</div>
<!-- banner -->
<!-- top-brands -->
	<?php 
		$product_list = array(
			array(
				'img_url' => site_url('assets/images/1.png'),
				'name' => 'Lorem ipsum dolor',
				'amount' => rand(0, 100000),
				'discount_amount' => rand(20, 500000),
				'currency_code' => 'USD',
			),
			array(
				'img_url' => site_url('assets/images/2.png'),
				'name' => 'Lorem ipsum dolor',
				'amount' => rand(0, 100000),
				'discount_amount' => rand(20, 500000),
				'currency_code' => 'USD',
			),
			array(
				'img_url' => site_url('assets/images/3.png'),
				'name' => 'Lorem ipsum dolor',
				'amount' => rand(0, 100000),
				'discount_amount' => rand(20, 500000),
				'currency_code' => 'USD',
			),
			array(
				'img_url' => site_url('assets/images/4.png'),
				'name' => 'Lorem ipsum dolor',
				'amount' => rand(0, 100000),
				'discount_amount' => rand(20, 500000),
				'currency_code' => 'USD',
			),
		);
	 ?>
	<div class="top-brands">
		<div class="container">
			<h3><?php echo lang('STD.std_offers') ?></h3>
			<div class="agile_top_brands_grids">

				<?php 
					foreach ($product_list as $product) {
						?>
				<div class="col-md-3 top_brand_left">
					<div class="hover14 column">
						<div class="agile_top_brand_left_grid">
							<div class="tag"><img src="images/tag.png" alt=" " class="img-responsive" /></div>
							<div class="agile_top_brand_left_grid1">
								<figure>
									<div class="snipcart-item block" >
										<div class="snipcart-thumb">
											<a href="single.html"><img title=" " alt=" " src="<?php echo $product['img_url']; ?>" /></a>		
											<p><?php echo $product['name']; ?></p>
											<h4><?php echo $product['amount']; ?> Ar <span><?php echo $product['discount_amount']; ?> Ar</span></h4>
										</div>
										<div class="snipcart-details top_brand_home_details">
											<form action="<?php echo site_url('home/checkout') ?>" method="post">
												<fieldset>
													<input type="hidden" name="cmd" value="_cart" />
													<input type="hidden" name="add" value="1" />
													<input type="hidden" name="business" value=" " />
													<input type="hidden" name="item_name" value="<?php echo $product['name']; ?>" />
													<input type="hidden" name="amount" value="<?php echo $product['amount']; ?>" />
													<input type="hidden" name="discount_amount" value="<?php echo $product['discount_amount']; ?>" />
													<input type="hidden" name="currency_code" value="<?php echo $product['currency_code']; ?>" />
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
		</div>
	</div>
<!-- //top-brands -->

<?= $this->endSection() ?>