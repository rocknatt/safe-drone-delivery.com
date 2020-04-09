<?php namespace Config;

/**
 * --------------------------------------------------------------------
 * URI Routing
 * --------------------------------------------------------------------
 * This file lets you re-map URI requests to specific controller functions.
 *
 * Typically there is a one-to-one relationship between a URL string
 * and its corresponding controller class/method. The segments in a
 * URL normally follow this pattern:
 *
 *    example.com/class/method/id
 *
 * In some instances, however, you may want to remap this relationship
 * so that a different class/function is called than the one
 * corresponding to the URL.
 */

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
// $routes->options('/', 'Home::options');

/**
 * --------------------------------------------------------------------
 * REST Route Definitions
 * --------------------------------------------------------------------
 */

// account
// $routes->presenter('account');
// $routes->resource('account');
$routes->post('account', 'Account::create');   // alias
$routes->get('accont', 'Account::index');
$routes->get('account/(:segment)', 'Account::profil/$1');
$routes->put('account', 'Account::update');
$routes->get('account/image/(:segment)', 'Account::image/$1');
$routes->post('account/image/(:segment)', 'Account::upload_image/$1');
$routes->get('account/list/client', 'Account::get_client_list');
$routes->get('account/list/followed', 'Account::get_user_followed_list');
$routes->post('account/client', 'Account::create_client/$1');

//prospect
$routes->get('prospect', 'Prospect::index');
$routes->get('prospect/(:segment)', 'Prospect::read/$1');
$routes->post('prospect', 'Prospect::create');
$routes->put('prospect', 'Prospect::update');

//quotation
$routes->get('quotation', 'Quotation::index');
$routes->get('quotation/(:segment)', 'Quotation::read/$1');
$routes->post('quotation', 'Quotation::create');
$routes->put('quotation', 'Quotation::update');
$routes->delete('quotation', 'Quotation::delete');
$routes->get('quotation/line/(:segment)', 'Quotation::get_line_list/$1');
$routes->post('quotation/line', 'Quotation::line_create');
$routes->put('quotation/line', 'Quotation::line_update');
$routes->delete('quotation/line', 'Quotation::line_delete');

//entity
$routes->get('entity', 'Entity::index');
$routes->get('entity/(:segment)', 'Entity::read/$1');
$routes->get('entity/select/(:segment)', 'Entity::get_select_list/$1');
$routes->post('entity', 'Entity::create');
$routes->put('entity', 'Entity::update');
$routes->delete('entity', 'Entity::delete');

//validate
$routes->post('validate/prospect', 'Prospect::set_state_validate');
$routes->post('validate/quotation', 'Quotation::set_state_validate');
$routes->post('validate/note', 'Note::set_state_validated');
$routes->post('validate/note_line', 'Note::line_validate');

//fold
$routes->post('fold/note_line', 'Note::line_fold');

//access
$routes->get('access/prospect', 'Prospect::get_access');
$routes->get('access/account', 'Account::get_access');
$routes->get('access/role', 'UserRole::get_access');
$routes->get('access/quotation', 'Quotation::get_access');
$routes->get('access/entity', 'Entity::get_access');

//sort
$routes->get('sort/account', 'Account::get_order_list');
$routes->get('sort/role', 'UserRole::get_order_list');
$routes->get('sort/mail', 'Mail::get_order_list');
$routes->get('sort/quotation', 'Quotation::get_order_list');
$routes->get('sort/entity', 'Entity::get_order_list');

//structure
$routes->get('structure/entity', 'Entity::get_form');
$routes->get('structure/account/client/(:segment)', 'Account::get_client_form/$1');
$routes->get('structure/quotation', 'Quotation::get_data_structure');

//share
$routes->post('share/calendar', 'Calendar::share');

//count
$routes->get('count/mail', 'Mail::mail_count');

//home
$routes->get('lang', 'Home::get_lang');
$routes->post('lang', 'Home::set_lang');
$routes->get('identity', 'Home::get_current_user');

//role
$routes->get('role', 'UserRole::index');
$routes->get('role/(:segment)', 'UserRole::read/$1');
$routes->post('role', 'UserRole::create');
$routes->put('role', 'UserRole::update');
$routes->delete('role', 'UserRole::delete');

//mail
$routes->get('mail', 'Mail::index');
$routes->get('mail/(:segment)', 'Mail::read/$1');
$routes->post('mail', 'Mail::create');
$routes->put('mail', 'Mail::update');
$routes->delete('mail', 'Mail::delete');
$routes->get('mail/attachement/(:segment)', 'Mail::download_attachement/$1');
$routes->post('mail/attachement/(:segment)', 'Mail::upload_attachement/$1');
$routes->post('mail/image/(:segment)', 'Mail::upload_image/$1');
$routes->post('mail/box/(:segment)', 'Mail::move/$1');

//param
$routes->get('param/mail', 'MailParam::index');
$routes->post('param/mail', 'MailParam::create');
$routes->put('param/mail', 'MailParam::update');
$routes->delete('param/mail', 'MailParam::delete');
$routes->get('param/mail/default', 'MailParam::default');

//image
$routes->get('image', 'Image::index');
$routes->post('image', 'Image::upload');
$routes->get('image/(:segment)', 'Image::download/$1');
$routes->get('image/(:segment)/(:segment)', 'Image::download/$1/$2');

//crystal
$routes->get('crystal/table', 'Crystal::get_table');
$routes->post('crystal/table', 'Crystal::table_create');
$routes->get('crystal/table/(:segment)', 'Crystal::table_read/$1');
$routes->put('crystal/table', 'Crystal::table_update');
$routes->delete('crystal/table', 'Crystal::table_delete');
$routes->put('crystal/table/sort', 'Crystal::table_sort');
$routes->put('crystal/session', 'Crystal::table_session');
$routes->get('crystal/session', 'Crystal::get_table_session');

$routes->get('crystal/column', 'Crystal::get_column');
$routes->post('crystal/column', 'Crystal::column_create');
$routes->get('crystal/column/(:segment)', 'Crystal::column_read/$1');
$routes->put('crystal/column', 'Crystal::column_update');
$routes->delete('crystal/column', 'Crystal::column_delete');
$routes->put('crystal/column/sort', 'Crystal::column_sort');

//note
$routes->get('note', 'Note::index');
$routes->post('note', 'Note::create');
$routes->get('note/(:segment)', 'Note::read/$1');
$routes->put('note', 'Note::update');
$routes->delete('note', 'Note::delete');
$routes->put('note/session', 'Note::update_session');
$routes->put('note/sort', 'Note::sort');

//calendar
$routes->get('calendar', 'Calendar::index');
$routes->get('calendar/(:segment)', 'Calendar::read/$1');
$routes->post('calendar', 'Calendar::create');
$routes->put('calendar', 'Calendar::update');
$routes->delete('calendar', 'Calendar::delete');
$routes->put('calendar/session', 'Calendar::update_session');

//shop
$routes->get('shop', 'App\Controllers\Mzara\Shop::index');
$routes->post('shop', 'App\Controllers\Mzara\Shop::create');
$routes->put('shop', 'App\Controllers\Mzara\Shop::update');
$routes->put('shop/setting/chat', 'App\Controllers\Mzara\Shop::update_setting_chat');
$routes->get('shop/setting/(:segment)', 'App\Controllers\Mzara\Shop::setting/$1');
$routes->get('shop/category/(:segment)', 'App\Controllers\Mzara\Shop::index/$1');
$routes->get('shop/list/shop', 'App\Controllers\Mzara\Shop::get_shop');
$routes->get('shop/list/category', 'App\Controllers\Mzara\Shop::get_shop_type_list');
$routes->get('shop/list/top', 'App\Controllers\Mzara\Shop::get_top_shop');
$routes->get('shop/list/subscribed', 'App\Controllers\Mzara\Shop::get_shop_subsribed');
$routes->post('shop/subscribe', 'App\Controllers\Mzara\Shop::subscribe');
$routes->get('shop/list/suggest', 'App\Controllers\Mzara\Shop::get_user_suggest_shop');
$routes->get('shop/list/owned', 'App\Controllers\Mzara\Shop::get_owned_shop');
$routes->get('shop/select/category', 'App\Controllers\Mzara\Shop::get_shop_category_select_list');
$routes->get('shop/image/(:segment)', 'App\Controllers\Mzara\Shop::image/$1');
$routes->get('shop/cover/(:segment)', 'App\Controllers\Mzara\Shop::cover_image/$1');
$routes->get('shop/(:segment)', 'App\Controllers\Mzara\Shop::read/$1');
$routes->post('shop/love', 'App\Controllers\Mzara\Shop::love');
$routes->post('shop/image', 'App\Controllers\Mzara\Shop::upload_image');
$routes->post('shop/cover', 'App\Controllers\Mzara\Shop::upload_cover_image');
$routes->get('structure/shop/create', 'App\Controllers\Mzara\Shop::get_form');
$routes->get('structure/shop/general/(:segment)', 'App\Controllers\Mzara\Shop::get_general_setting_form/$1');
$routes->get('structure/shop/chat/(:segment)', 'App\Controllers\Mzara\Shop::get_chat_setting_form/$1');

// product
$routes->get('product', 'App\Controllers\Mzara\Product::index');
$routes->post('product', 'App\Controllers\Mzara\Product::create');
$routes->put('product', 'App\Controllers\Mzara\Product::update');
$routes->delete('product', 'App\Controllers\Mzara\Product::delete');
$routes->get('product/category/(:segment)', 'App\Controllers\Mzara\Product::index/$1');
$routes->get('product/list/product', 'App\Controllers\Mzara\Product::get_list');
$routes->get('product/list/category', 'App\Controllers\Mzara\Product::get_product_category_list');
$routes->get('product/list/brand', 'App\Controllers\Mzara\Product::get_product_brand_list');
$routes->get('product/list/suggest', 'App\Controllers\Mzara\Product::get_suggest_list');
$routes->get('product/select/category', 'App\Controllers\Mzara\Product::get_product_category_select_list');
$routes->get('product/select/brand', 'App\Controllers\Mzara\Product::get_product_brand_select_list');
$routes->get('product/select/brand', 'App\Controllers\Mzara\Product::get_product_brand_select_list');
$routes->get('product/(:segment)', 'App\Controllers\Mzara\Product::read/$1');
$routes->post('product/love', 'App\Controllers\Mzara\Product::love');
$routes->post('product/image', 'App\Controllers\Mzara\Product::upload_image');
$routes->delete('product/image', 'App\Controllers\Mzara\Product::delete_image');
$routes->get('structure/product/edit/(:segment)', 'App\Controllers\Mzara\Product::get_edit_form/$1');
$routes->get('structure/product/setting/(:segment)', 'App\Controllers\Mzara\Product::get_setting_form/$1');
$routes->get('structure/product/tag/(:segment)', 'App\Controllers\Mzara\Product::get_tag_form/$1');

// tag
$routes->get('tag/select', 'App\Controllers\Mzara\Tag::get_select_list');
$routes->post('tag', 'App\Controllers\Mzara\Tag::create');

//event
$routes->get('event', 'App\Controllers\Mzara\Event::index');
$routes->get('event/category/(:segment)', 'App\Controllers\Mzara\Event::index/$1');
$routes->get('event/list/event', 'App\Controllers\Mzara\Event::get_list');
$routes->get('event/list/category', 'App\Controllers\Mzara\Event::get_event_type_list');
$routes->get('event/list/suggest', 'App\Controllers\Mzara\Event::get_suggest_list');
$routes->get('event/select/category', 'App\Controllers\Mzara\Event::get_event_type_select_list');
$routes->get('event/(:segment)', 'App\Controllers\Mzara\Event::read/$1');
$routes->post('event/love', 'App\Controllers\Mzara\Event::love');

// post
$routes->get('post', 'App\Controllers\Mzara\Post::index');
$routes->get('post/category/(:segment)', 'App\Controllers\Mzara\Post::index/$1');
$routes->get('post/list/post', 'App\Controllers\Mzara\Post::get_list');
$routes->get('post/list/comment', 'App\Controllers\Mzara\Post::get_comment_list');
$routes->get('post/(:segment)', 'App\Controllers\Mzara\Post::read/$1');

//account - client side
// post
$routes->get('account', 'App\Controllers\Mzara\Account::index');
$routes->get('account/signup', 'App\Controllers\Mzara\Account::signup');
$routes->get('account/setting', 'App\Controllers\Mzara\Account::setting');
$routes->post('account/signup', 'App\Controllers\Mzara\Account::signup_submit');
$routes->post('account/setting', 'App\Controllers\Mzara\Account::setting_name_submit');
$routes->post('account/interest', 'App\Controllers\Mzara\Account::interest_submit');
$routes->post('account/notification', 'App\Controllers\Mzara\Account::setting_notification');
$routes->post('account/image', 'App\Controllers\Mzara\Account::upload_image');
$routes->post('account/cover', 'App\Controllers\Mzara\Account::upload_cover_image');
$routes->get('account/category/(:segment)', 'App\Controllers\Mzara\Account::index/$1');
$routes->get('account/list/account', 'App\Controllers\Mzara\Account::get_list');
$routes->get('account/mzara/(:segment)', 'App\Controllers\Mzara\Account::read/$1');
$routes->get('structure/account/signup', 'App\Controllers\Mzara\Account::get_signup_form');
$routes->get('structure/account/forgot_password', 'App\Controllers\Mzara\Account::get_forgot_password_form');
$routes->get('structure/account/setting', 'App\Controllers\Mzara\Account::get_setting_form');
$routes->get('select/account/view_name', 'App\Controllers\Mzara\Account::get_view_name_suggest');
$routes->post('account/send_code', 'App\Controllers\Mzara\Account::send_code');
$routes->post('account/check_code', 'App\Controllers\Mzara\Account::check_code');

//chat
$routes->get('chat', 'Chat::index');
$routes->get('chat/group', 'Chat::get_chat_group_list');
$routes->get('chat/group/(:segment)', 'Chat::group_bubble/$1');
$routes->post('chat/group', 'Chat::group_create');
$routes->put('chat/group', 'Chat::group_set_metadata');
$routes->delete('chat/group', 'Chat::group_delete');
$routes->post('chat/group/user', 'Chat::group_add_user');
$routes->put('chat/group/user', 'Chat::group_role_user');
$routes->delete('chat/group/user', 'Chat::group_remove_user');
$routes->get('chat/group/image/(:segment)', 'Chat::get_image_profil/$1');
$routes->post('chat/group/image', 'Chat::group_upload_profil_picture');

$routes->post('chat/block', 'Chat::block_chat_group');
$routes->delete('chat/block', 'Chat::unblock_chat_group');

$routes->get('chat/message/(:segment)', 'Chat::get_chat_group_message/$1');
$routes->post('chat/message', 'Chat::send_chat_group_message');
$routes->delete('chat/message', 'Chat::delete');

$routes->get('chat/seen/(:segment)', 'Chat::get_chat_user_seen/$1');
$routes->post('chat/seen', 'Chat::mark_read');
$routes->delete('chat/seen', 'Chat::mark_unread');

$routes->get('chat/resume/file/(:segment)', 'Chat::get_chat_file_list/$1');
$routes->get('chat/resume/image/(:segment)', 'Chat::get_chat_image_list/$1');
$routes->get('chat/resume/user/(:segment)', 'Chat::get_group_user_list/$1');
$routes->get('chat/user/(:segment)', 'Chat::bubble_user/$1');
$routes->get('chat/shop/(:segment)', 'Chat::bubble_shop/$1');
$routes->get('chat/image/(:segment)', 'Chat::get_chat_image/$1');
$routes->get('chat/file/(:segment)', 'Chat::get_attachment/$1');

$routes->get('chat/emoji', 'Chat::get_emoji_database');

$routes->get('chat/(:segment)', 'Chat::read/$1');
$routes->post('chat', 'Chat::create');
$routes->put('chat', 'Chat::update');
$routes->delete('chat', 'Chat::delete');


$routes->post('note_line', 'Note::line_create');
// $routes->get('note_line/(:segment)', 'Crystal::read/$1');
$routes->put('note_line', 'Note::line_update');
$routes->delete('note_line', 'Note::line_delete');
$routes->put('note_line/sort', 'Note::line_sort');

//options 'restful dev exception'
$routes->options('/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'Home::index');
$routes->options('/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'Home::index');
// $routes->delete('account/delete/(:segment)', 'Photos::update/$1');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
