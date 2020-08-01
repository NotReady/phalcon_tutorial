<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2020-07-19
 * Time: 18:50
 */

class GoogleCalenderAPIClient
{
    public static function getHoliday($start, $end)
    {
        // 取得したAPIキー
        $api_key = Secret::getGoogleApiKey();
        // カレンダーID
        $calendar_id = urlencode('japanese__ja@holiday.calendar.google.com');  // Googleの提供する日本の祝日カレンダー
        // データの開始日
        $start = date("{$start}\T00:00:00\Z");
        // データの終了日
        $end = date("{$end}\T00:00:00\Z");
        $url = "https://www.googleapis.com/calendar/v3/calendars/" . $calendar_id . "/events?";
        $query = [
            'key' => $api_key,
            'timeMin' => $start,
            'timeMax' => $end,
            'maxResults' => 50,
            'orderBy' => 'startTime',
            'singleEvents' => 'true'
        ];

        $results = [];
        if ($data = file_get_contents($url. http_build_query($query), true)) {
            $data = json_decode($data);
            // $data->itemには日本の祝日カレンダーの"予定"が入ってきます
            foreach ($data->items as $row) {
                // [予定の日付 => 予定のタイトル]
                $results[$row->start->date] = $row->summary;
            }
        }

        return $results;
    }
}