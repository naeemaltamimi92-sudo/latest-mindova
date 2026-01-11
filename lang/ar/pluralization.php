<?php

declare(strict_types=1);

/**
 * Arabic Pluralization Rules
 *
 * Arabic has 6 plural forms based on the count:
 * - Zero: 0
 * - One: 1
 * - Two: 2
 * - Few: 3-10
 * - Many: 11-99
 * - Other: 100+
 *
 * @package Lang\Ar
 * @author Mindova Team
 * @version 1.0.0
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Arabic Pluralization Rules
    |--------------------------------------------------------------------------
    |
    | This file defines the pluralization rules for Arabic language.
    | Arabic has one of the most complex plural systems with 6 different forms.
    |
    */

    'interval' => [
        // Zero form (0)
        0 => ':count',

        // One form (1)
        1 => ':count',

        // Two form (2)
        2 => ':count',

        // Few form (3-10)
        '3-10' => ':count',

        // Many form (11-99)
        '11-99' => ':count',

        // Other form (100+)
        '*' => ':count',
    ],

    /**
     * Get the appropriate plural form index based on count
     *
     * @param int $count
     * @return int Form index (0-5)
     */
    'form' => function (int $count): int {
        if ($count === 0) {
            return 0; // Zero
        } elseif ($count === 1) {
            return 1; // One
        } elseif ($count === 2) {
            return 2; // Two
        } elseif ($count >= 3 && $count <= 10) {
            return 3; // Few
        } elseif ($count >= 11 && $count <= 99) {
            return 4; // Many
        } else {
            return 5; // Other (100+)
        }
    },

    /*
    |--------------------------------------------------------------------------
    | Common Plural Examples
    |--------------------------------------------------------------------------
    |
    | Examples of how to use Arabic pluralization in your translations:
    |
    | "There is no user|There is one user|There are two users|There are :count users|There are :count users|There are :count users"
    |
    | 0 items: "لا يوجد عناصر"
    | 1 item:  "عنصر واحد"
    | 2 items: "عنصران"
    | 3-10:    ":count عناصر"
    | 11-99:   ":count عنصراً"
    | 100+:    ":count عنصر"
    |
    */

    'examples' => [
        'user' => 'لا يوجد مستخدمين|مستخدم واحد|مستخدمان|:count مستخدمين|:count مستخدماً|:count مستخدم',
        'item' => 'لا يوجد عناصر|عنصر واحد|عنصران|:count عناصر|:count عنصراً|:count عنصر',
        'file' => 'لا يوجد ملفات|ملف واحد|ملفان|:count ملفات|:count ملفاً|:count ملف',
        'message' => 'لا توجد رسائل|رسالة واحدة|رسالتان|:count رسائل|:count رسالةً|:count رسالة',
        'day' => 'لا توجد أيام|يوم واحد|يومان|:count أيام|:count يوماً|:count يوم',
        'hour' => 'لا توجد ساعات|ساعة واحدة|ساعتان|:count ساعات|:count ساعةً|:count ساعة',
        'minute' => 'لا توجد دقائق|دقيقة واحدة|دقيقتان|:count دقائق|:count دقيقةً|:count دقيقة',
        'second' => 'لا توجد ثوانٍ|ثانية واحدة|ثانيتان|:count ثوانٍ|:count ثانيةً|:count ثانية',
    ],

    /*
    |--------------------------------------------------------------------------
    | Time Units (for diffForHumans)
    |--------------------------------------------------------------------------
    */

    'ago' => 'منذ :time',
    'from_now' => 'بعد :time',
    'after' => 'بعد :time',
    'before' => 'قبل :time',

    'year' => 'لا توجد سنوات|سنة واحدة|سنتان|:count سنوات|:count سنةً|:count سنة',
    'month' => 'لا توجد أشهر|شهر واحد|شهران|:count أشهر|:count شهراً|:count شهر',
    'week' => 'لا توجد أسابيع|أسبوع واحد|أسبوعان|:count أسابيع|:count أسبوعاً|:count أسبوع',

    /*
    |--------------------------------------------------------------------------
    | Common Countable Nouns
    |--------------------------------------------------------------------------
    */

    'comment' => 'لا توجد تعليقات|تعليق واحد|تعليقان|:count تعليقات|:count تعليقاً|:count تعليق',
    'post' => 'لا توجد منشورات|منشور واحد|منشوران|:count منشورات|:count منشوراً|:count منشور',
    'like' => 'لا توجد إعجابات|إعجاب واحد|إعجابان|:count إعجابات|:count إعجاباً|:count إعجاب',
    'view' => 'لا توجد مشاهدات|مشاهدة واحدة|مشاهدتان|:count مشاهدات|:count مشاهدةً|:count مشاهدة',
    'download' => 'لا توجد تنزيلات|تنزيل واحد|تنزيلان|:count تنزيلات|:count تنزيلاً|:count تنزيل',

    'notification' => 'لا توجد إشعارات|إشعار واحد|إشعاران|:count إشعارات|:count إشعاراً|:count إشعار',
    'task' => 'لا توجد مهام|مهمة واحدة|مهمتان|:count مهام|:count مهمةً|:count مهمة',
    'project' => 'لا توجد مشاريع|مشروع واحد|مشروعان|:count مشاريع|:count مشروعاً|:count مشروع',
    'team' => 'لا توجد فرق|فريق واحد|فريقان|:count فرق|:count فريقاً|:count فريق',

    'document' => 'لا توجد مستندات|مستند واحد|مستندان|:count مستندات|:count مستنداً|:count مستند',
    'page' => 'لا توجد صفحات|صفحة واحدة|صفحتان|:count صفحات|:count صفحةً|:count صفحة',
    'result' => 'لا توجد نتائج|نتيجة واحدة|نتيجتان|:count نتائج|:count نتيجةً|:count نتيجة',

    'error' => 'لا توجد أخطاء|خطأ واحد|خطآن|:count أخطاء|:count خطأً|:count خطأ',
    'warning' => 'لا توجد تحذيرات|تحذير واحد|تحذيران|:count تحذيرات|:count تحذيراً|:count تحذير',
];
