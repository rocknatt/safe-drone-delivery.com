<?= $this->extend('layout/index') ?>

<?= $this->section('content') ?>

<!-- products-breadcrumb -->
	<div class="products-breadcrumb">
		<div class="container">
			<ul>
				<li><i class="fa fa-home" aria-hidden="true"></i><a href="index.html">Home</a><span>|</span></li>
				<li>Payment</li>
			</ul>
		</div>
	</div>
<!-- //products-breadcrumb -->
<!-- banner -->
	<div class="banner">
		<?= $this->include('layout/side-menu') ?>

		<div class="w3l_banner_nav_right">
<!-- payment -->
		<div class="privacy about">
			<h3><?php echo lang('STD.std_payement') ?></h3>
			
	         <div class="checkout-right">
				<!--Horizontal Tab-->
		        <div id="parentHorizontalTab">
		            <ul class="resp-tabs-list hor_1">
						<li>MVola</li>
		                <li>Orange Money</li>
		                <li>Paypal</li>
		            </ul>
		            <div class="resp-tabs-container hor_1">

						<div>
		                    <form action="#" method="post" class="creditly-card-form agileinfo_form">
								<section class="creditly-wrapper wthree, w3_agileits_wrapper">
									<div class="credit-card-wrapper">
										<div class="first-row form-group">
											<div class="controls">
												<label class="control-label"><?php echo lang('STD.std_phone') ?>:</label>
									    		<input class="form-control" type="text" placeholder="<?php echo lang('STD.std_your_phone_number') ?>">

											</div>
										</div>
										<button class="btn btn-primary submit"><span><?php echo lang('STD.std_make_payement') ?> </span></button>
									</div>
								</section>
							</form>
		                </div>
		                <div>
		                    <form action="#" method="post" class="creditly-card-form agileinfo_form">
								<section class="creditly-wrapper wthree, w3_agileits_wrapper">
									<div class="credit-card-wrapper">
										<div class="first-row form-group">
											<div class="controls">
												<label class="control-label"><?php echo lang('STD.std_phone') ?>:</label>
									    		<input class="form-control" type="text" placeholder="<?php echo lang('STD.std_your_phone_number') ?>">

											</div>
										</div>
										<button class="btn btn-primary submit"><span><?php echo lang('STD.std_make_payement') ?> </span></button>
									</div>
								</section>
							</form>
		                </div>
		                <div>
		                    <div id="tab4" class="tab-grid" style="display: block;">
									<div class="row">
		                        <div class="col-md-6">
		                            <img class="pp-img" src="img/paypal.png" alt="Image Alternative text" title="Image Title">
		                            <p>Important: You will be redirected to PayPal's website to securely complete your payment.</p><a class="btn btn-primary">Checkout via Paypal</a>	
		                        </div>
		                        <div class="col-md-6">
		                            <form class="cc-form">
		                                <div class="clearfix">
		                                    <div class="form-group form-group-cc-number">
		                                        <label>Card Number</label>
		                                        <input class="form-control" placeholder="xxxx xxxx xxxx xxxx" type="text"><span class="cc-card-icon"></span>
		                                    </div>
		                                    <div class="form-group form-group-cc-cvc">
		                                        <label>CVV</label>
		                                        <input class="form-control" placeholder="xxxx" type="text">
		                                    </div>
		                                </div>
		                                <div class="clearfix">
		                                    <div class="form-group form-group-cc-name">
		                                        <label>Card Holder Name</label>
		                                        <input class="form-control" type="text">
		                                    </div>
		                                    <div class="form-group form-group-cc-date">
		                                        <label>Valid Thru</label>
		                                        <input class="form-control" placeholder="mm/yy" type="text">
		                                    </div>
		                                </div>
		                                <div class="checkbox checkbox-small">
		                                    <label>
		                                        <input class="i-check" type="checkbox" checked="">Add to My Cards</label>
		                                </div>
		                                <input class="btn btn-primary submit" type="submit" class="submit" value="Proceed Payment">
		                            </form>
		                        </div>
		                    </div>
		                        
								</div>
		                </div>
		                
		            </div>
		        </div>
	
	<!--Plug-in Initialisation-->

	<!-- // Pay -->
	
			 </div>

		</div>
<!-- //payment -->
		</div>
		<div class="clearfix"></div>
	</div>
<!-- //banner -->

<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/easy-responsive-tabs.css') ?>" />
<script src="<?php echo site_url('assets/js/easyResponsiveTabs.js') ?>"></script>
<!-- //easy-responsive-tabs --> 
	<script type="text/javascript">
    $(document).ready(function() {
        //Horizontal Tab
        $('#parentHorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function(event) { // Callback function if tab is switched
                var $tab = $(this);
                var $info = $('#nested-tabInfo');
                var $name = $('span', $info);
                $name.text($tab.text());
                $info.show();
            }
        });
    });
</script>
<?= $this->endSection() ?>