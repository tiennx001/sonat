<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define('DB_NAME', 'sonat');

/** Username của database */
define('DB_USER', 'root');
//define('DB_USER', 'sonat');

/** Mật khẩu của database */
define('DB_PASSWORD', '');
//define('DB_PASSWORD', 'MonnetaA@123!@#');

/** Hostname của database */
define('DB_HOST', 'localhost');

/** Database charset sử dụng để tạo bảng database. */
define('DB_CHARSET', 'utf8mb4');

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '>_sY!>7cs2~asy:|C tQ(U-JKFUbe+$s5Wv8PiND^Ll$Q.=XQ8B*6q}.?}Yp51oH');
define('SECURE_AUTH_KEY',  'V{(5oxm4ru{2E4a~$spY<VJeRYt`xLJJ<b-EZp]G3p%xb4tx_DTctGh3XuUC!H2#');
define('LOGGED_IN_KEY',    '4p|^z$=?,S!f#_pfQP5<&K~`Z.fRr5#i-O&_Az[7L3&+yo;nhh%g#b^oT;4el<=^');
define('NONCE_KEY',        '7sT}J.1]u]?k|rJ2X&NfII6]qp9u}N~ki_>ogh(I^vpjVYQ-c3h,p:$JuPTJNgVY');
define('AUTH_SALT',        'Zg*m!VuG6|cRm1f6%dc2dwNq -<c9]%3^O0OZj4WpM*>tJx_@M8xi(HO{]]v6qFn');
define('SECURE_AUTH_SALT', 'q(t[Pir{;]??KA%RZy[4XcP%ps}]VleQr6keCY{nLu<l+I4DtEv^Gk~?:p_386?o');
define('LOGGED_IN_SALT',   'ybSdjg*P]?%f0&a,um#jxP[ppYvt{$5j`c,?}Jv)=J^nPwBX7Ja1nMX~,LKLztWn');
define('NONCE_SALT',       '9}(^87^Z:0|)eX_ThZFqeF$(q{sheo~o%W%:!>W_2skoZ%i2*XlBuRk;p`a?yrO$');

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix  = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
