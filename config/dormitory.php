<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dormitory Management System Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho hệ thống quản lý ký túc xá
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Room Settings
    |--------------------------------------------------------------------------
    */
    'room' => [
        'max_capacity' => env('DORMITORY_MAX_ROOM_CAPACITY', 8),
        'min_capacity' => env('DORMITORY_MIN_ROOM_CAPACITY', 1),
        'default_area' => 20, // m²
    ],

    /*
    |--------------------------------------------------------------------------
    | Contract Settings
    |--------------------------------------------------------------------------
    */
    'contract' => [
        'min_duration_months' => env('DORMITORY_CONTRACT_MIN_DURATION_MONTHS', 1),
        'max_duration_months' => env('DORMITORY_CONTRACT_MAX_DURATION_MONTHS', 12),
        'default_payment_cycle' => 'monthly', // monthly, semester, yearly
        'deposit_percentage' => 20, // % of monthly rent
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'due_days' => env('DORMITORY_PAYMENT_DUE_DAYS', 7), // Days after invoice issue
        'late_penalty_percent' => env('DORMITORY_LATE_PAYMENT_PENALTY_PERCENT', 5),
        'methods' => [
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'vnpay' => 'VNPay',
            'momo' => 'MoMo',
            'zalopay' => 'ZaloPay',
        ],
        'vnpay' => [
            'tmn_code' => env('VNPAY_TMN_CODE', ''),
            'hash_secret' => env('VNPAY_HASH_SECRET', ''),
            'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
            'return_url' => env('VNPAY_RETURN_URL', rtrim(env('APP_URL', 'http://127.0.0.1:8000'), '/') . '/payment/return'),
            'ipn_url' => env('VNPAY_IPN_URL', rtrim(env('APP_URL', 'http://127.0.0.1:8000'), '/') . '/payment/ipn'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Settings
    |--------------------------------------------------------------------------
    */
    'invoice' => [
        'types' => [
            'rent' => 'Tiền thuê phòng',
            'electricity' => 'Tiền điện',
            'water' => 'Tiền nước',
            'service' => 'Phí dịch vụ',
            'other' => 'Chi phí khác',
        ],
        'electricity_price_per_kwh' => 3000, // VNĐ
        'water_price_per_m3' => 15000, // VNĐ
        'service_fee_per_month' => 50000, // VNĐ
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Request Settings
    |--------------------------------------------------------------------------
    */
    'maintenance' => [
        'categories' => [
            'plumbing' => 'Hệ thống nước',
            'electrical' => 'Hệ thống điện',
            'furniture' => 'Đồ nội thất',
            'air_conditioning' => 'Điều hòa',
            'door_lock' => 'Cửa và khóa',
            'window' => 'Cửa sổ',
            'other' => 'Khác',
        ],
        'priorities' => [
            'low' => ['label' => 'Thấp', 'response_hours' => 168], // 1 week
            'medium' => ['label' => 'Trung bình', 'response_hours' => 72], // 3 days
            'high' => ['label' => 'Cao', 'response_hours' => 24], // 1 day
            'urgent' => ['label' => 'Khẩn cấp', 'response_hours' => 4], // 4 hours
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Violation Settings
    |--------------------------------------------------------------------------
    */
    'violation' => [
        'severities' => [
            'minor' => ['label' => 'Nhẹ', 'default_fine' => 50000],
            'moderate' => ['label' => 'Trung bình', 'default_fine' => 200000],
            'serious' => ['label' => 'Nghiêm trọng', 'default_fine' => 500000],
            'critical' => ['label' => 'Rất nghiêm trọng', 'default_fine' => 1000000],
        ],
        'max_warnings' => env('DORMITORY_VIOLATION_MAX_WARNINGS', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | Announcement Settings
    |--------------------------------------------------------------------------
    */
    'announcement' => [
        'types' => [
            'general' => 'Thông báo chung',
            'maintenance' => 'Bảo trì',
            'payment' => 'Thanh toán',
            'event' => 'Sự kiện',
            'emergency' => 'Khẩn cấp',
            'rule' => 'Nội quy',
        ],
        'priorities' => [
            'low' => 'Thấp',
            'medium' => 'Trung bình',
            'high' => 'Cao',
            'urgent' => 'Khẩn cấp',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Roles
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'admin' => 'Quản trị viên',
        'manager' => 'Quản lý',
        'student' => 'Sinh viên',
    ],

    /*
    |--------------------------------------------------------------------------
    | Room Status
    |--------------------------------------------------------------------------
    */
    'room_status' => [
        'available' => 'Còn trống',
        'occupied' => 'Đã đầy',
        'maintenance' => 'Đang sửa chữa',
        'reserved' => 'Đã đặt',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gender Options
    |--------------------------------------------------------------------------
    */
    'genders' => [
        'male' => 'Nam',
        'female' => 'Nữ',
        'mixed' => 'Cả hai',
    ],
];
