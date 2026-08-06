<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * Danh sách dịch vụ hiển thị ở trang chủ (section #services) và dùng để
     * validate lựa chọn dịch vụ trong form đặt lịch (#booking).
     *
     * Khai báo tĩnh tạm thời để có UI hoạt động ngay - sau này có thể thay
     * bằng dữ liệu thật lấy từ bảng `services` trong database.
     */
    public static function salonServices(): array
    {
        return [
            'cat-toc' => [
                'name'  => 'Cắt tóc tạo kiểu',
                'icon'  => 'icon-cut',
                'price' => '150.000đ',
                'desc'  => 'Cắt & tạo kiểu tóc theo xu hướng, phù hợp gương mặt.',
            ],
            'nhuom-uon-toc' => [
                'name'  => 'Nhuộm / Uốn tóc',
                'icon'  => 'icon-magic',
                'price' => '450.000đ',
                'desc'  => 'Nhuộm, uốn, duỗi bằng dưỡng chất cao cấp, an toàn cho tóc.',
            ],
            'cham-soc-da' => [
                'name'  => 'Chăm sóc da mặt',
                'icon'  => 'flaticon-facial-treatment',
                'price' => '350.000đ',
                'desc'  => 'Làm sạch sâu, cấp ẩm, trẻ hoá làn da cùng chuyên gia.',
            ],
            'massage' => [
                'name'  => 'Massage thư giãn',
                'icon'  => 'icon-heartbeat',
                'price' => '400.000đ',
                'desc'  => 'Massage body/foot giúp thư giãn, giảm căng thẳng, mệt mỏi.',
            ],
            'trang-diem' => [
                'name'  => 'Trang điểm chuyên nghiệp',
                'icon'  => 'icon-star',
                'price' => '300.000đ',
                'desc'  => 'Trang điểm dự tiệc, cô dâu, chụp ảnh theo yêu cầu.',
            ],
            'nail' => [
                'name'  => 'Chăm sóc móng (Nail)',
                'icon'  => 'flaticon-cosmetics',
                'price' => '200.000đ',
                'desc'  => 'Làm móng, sơn gel, nail art theo mẫu yêu thích.',
            ],
        ];
    }
}
