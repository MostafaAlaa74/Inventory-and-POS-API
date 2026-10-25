<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تنبيه انخفاض المخزون</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f3f4f6; color: #1f2937; font-family: Arial, Tahoma, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="background-color: #f3f4f6; padding: 32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                    style="max-width: 600px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="padding: 28px 32px; background-color: #172554; text-align: right;">
                            <p
                                style="margin: 0 0 8px; color: #bfdbfe; font-size: 13px; font-weight: bold; letter-spacing: 0.5px;">
                                تنبيه المخزون</p>
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; line-height: 1.5;">الكمية أوشكت على
                                النفاد</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 20px; color: #374151; font-size: 16px; line-height: 1.8;">
                                نود تنبيهك إلى انخفاض كمية المنتجات التالية. يُرجى مراجعة المخزون وإعادة تعبئتها في أقرب وقت.
                            </p>

                            @forelse ($products as $product)
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                    style="margin-bottom: 16px; border: 1px solid #e5e7eb; border-radius: 8px;">
                                    <tr>
                                        <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                                            <p style="margin: 0 0 6px; color: #6b7280; font-size: 13px;">اسم المنتج</p>
                                            <p dir="auto"
                                                style="margin: 0; color: #111827; font-size: 17px; font-weight: bold; line-height: 1.5;">
                                                {{ $product->name }}</p>
                                        </td>
                                    </tr>
                                    @if ($product->SKU)
                                        <tr>
                                            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                                                <p style="margin: 0 0 6px; color: #6b7280; font-size: 13px;">رمز المنتج (SKU)</p>
                                                <p dir="ltr" style="margin: 0; color: #111827; font-size: 15px;">
                                                    {{ $product->SKU }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                                            <p style="margin: 0 0 6px; color: #6b7280; font-size: 13px;">الكمية المتوفرة حاليًا</p>
                                            <p style="margin: 0; color: #b91c1c; font-size: 22px; font-weight: bold;">
                                                {{ $product->stock_quantity }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 16px 20px;">
                                            <p style="margin: 0 0 6px; color: #6b7280; font-size: 13px;">حد التنبيه للمخزون المنخفض</p>
                                            <p style="margin: 0; color: #111827; font-size: 16px;">
                                                {{ $product->low_stock_threshold }}</p>
                                        </td>
                                    </tr>
                                </table>
                            @empty
                                <p style="margin: 0; padding: 16px 20px; color: #6b7280; font-size: 14px; border: 1px solid #e5e7eb; border-radius: 8px;">
                                    لا توجد منتجات لعرضها في هذا التنبيه.
                                </p>
                            @endforelse

                            <p style="margin: 24px 0 0; color: #6b7280; font-size: 13px; line-height: 1.7;">
                                هذه رسالة تلقائية من نظام إدارة المخزون. يُرجى عدم الرد عليها.
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="margin: 16px 0 0; color: #9ca3af; font-size: 12px;">نظام إدارة المخزون ونقاط البيع</p>
            </td>
        </tr>
    </table>
</body>

</html>
