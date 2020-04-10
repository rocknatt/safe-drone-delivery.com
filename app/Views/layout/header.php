<!-- header -->
	<div class="agileits_header">
		<div class="w3l_search">
			<form action="#" method="post">
				<input type="text" name="Product" value="<?php echo lang('STD.std_search') ?>" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search a product...';}" required="">
				<button type="submit">
					<i class="fa fa-search"></i>
				</button>
			</form>
		</div>
		<div class="product_list_header">  
			<form action="#" method="post" class="last">
                <fieldset>
                    <input type="hidden" name="cmd" value="_cart" />
                    <input type="hidden" name="display" value="1" />
                    <input type="submit" name="submit" value="<?php echo lang('STD.std_view_cart') ?>" class="button" />
                </fieldset>
            </form>
		</div>
		<div class="w3l_header_right">
			<ul>
				<li class="dropdown profile_details_drop">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user" aria-hidden="true"></i><span class="caret"></span></a>
					<div class="mega-dropdown-menu">
						<div class="w3ls_vegetables">
							<ul class="dropdown-menu drp-mnu">
								<li><a href="login.html">Login</a></li> 
								<li><a href="login.html">Sign Up</a></li>
							</ul>
						</div>                  
					</div>	
				</li>
			</ul>
		</div>
		<div class="w3l_header_right1">
			<h2><a href="mail.html"><?php echo lang('STD.std_contact') ?></a></h2>
		</div>
		<div class="clearfix"> </div>
	</div>
	<div class="logo_products">
		<div class="container">
			<div class="w3ls_logo_products_left">
				<a href="<?php echo site_url('home') ?>">
					<img src="<?php echo site_url('assets/images/logo.png') ?>" >
				</a>
			</div>
			<div class="w3ls_logo_products_left1">
				<ul class="special_items">
					<li><a href="<?php echo site_url('home') ?>"><?php echo lang('STD.std_home') ?></a><i>/</i></li>
					<li><a href="<?php echo site_url('home/shop') ?>"><?php echo lang('STD.std_shop') ?></a><i>/</i></li>
					<li><a href="<?php echo site_url('home/checkout') ?>"><?php echo lang('STD.std_checkout') ?></a><i>/</i></li>
					<li><a href="<?php echo site_url('home/about') ?>"><?php echo lang('STD.std_about') ?></a></li>
				</ul>
			</div>
			<div class="w3ls_logo_products_left1">
				<ul class="phone_email">
					<li><i class="fa fa-phone" aria-hidden="true"></i> +261 34 78 261 09</li>
					<li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:contact@safe-drone-delivery.com">contact@safe-drone-delivery.com</a></li>
				</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
<!-- //header -->