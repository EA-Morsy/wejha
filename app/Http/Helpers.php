<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use App\Models\Saved;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}
if (!function_exists('general_setting')) {
    function general_setting($key)
    {
        return Setting::where('key', $key)->first()?->value;
    }
}

if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = \App\Models\Media::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return "";
    }
}
if (!function_exists('getGovernment')) {
    function getGovernment($id = 0)
    {
        return $id == 0 ? '---' : (getGovernments()[$id] ?? '---');
    }
}
if (!function_exists('getGovernments')) {
    function getGovernments()
    {
        return [
            1 => 'القاهرة',
            2 => 'الجيزة',
            3 => 'الشرقية',
            4 => 'الدقهلية',
            5 => 'البحيرة',
            6 => 'المنيا',
            7 => 'القليوبية',
            8 => 'الإسكندرية',
            9 => 'الغربية',
            10 => 'سوهاج',
            11 => 'أسيوط',
            12 => 'المنوفية',
            13 => 'كفر الشيخ',
            14 => 'الفيوم',
            15 => 'قنا',
            16 => 'بني سويف',
            17 => 'أسوان',
            18 => 'دمياط',
            19 => 'الإسماعيلية',
            20 => 'الأقصر',
            21 => 'بورسعيد',
            22 => 'السويس',
            23 => 'مطروح',
            24 => 'شمال سيناء',
            25 => 'البحر الأحمر',
            26 => 'الوادي الجديد',
            27 => 'جنوب سيناء',
            28 => 'أطراف القاهرة والجيزة'
        ];
    }
}

if (!function_exists('multi_asset')) {
    function multi_asset($Ids)
    {
        $assets = Media::whereIn('id', $Ids)->get();

        if ((isset($assets))) {
            foreach ($assets as $key => $asset) {
                $files[$key]['name'] = $asset->file_original_name;
                $files[$key]['path'] = my_asset($asset->file_name);
                $files[$key]['ext'] = $asset->extension;
            }

            return $files;
        }

        return "";
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if (($asset = \App\Models\Media::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return null;
    }
}

//return file uploaded via uploader
if (!function_exists('first_asset')) {
    function first_asset($str)
    {
        if (!empty($str)) {
            $numbers = array_filter(preg_split("/\D+/", $str));
            $first_num = reset($numbers);
            if (($asset = \App\Models\Media::find($first_num)) != null) {
                return my_asset($asset->file_name);
            }
        }
        return null;
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return $url = url('/') . \Illuminate\Support\Facades\Storage::url($path);
            return app('url')->asset('/' . $path, $secure);
        }
    }
}

if (!function_exists('upload')) {
    function upload($file, $disk = '', $folder = '', $path = '')
    {
        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
            "mp4" => "video",
            "mpg" => "video",
            "mpeg" => "video",
            "webm" => "video",
            "ogg" => "video",
            "avi" => "video",
            "mov" => "video",
            "flv" => "video",
            "swf" => "video",
            "mkv" => "video",
            "wmv" => "video",
            "wma" => "audio",
            "aac" => "audio",
            "wav" => "audio",
            "mp3" => "audio",
            "zip" => "archive",
            "rar" => "archive",
            "7z" => "archive",
            "doc" => "document",
            "txt" => "document",
            "docx" => "document",
            "pdf" => "document",
            "csv" => "document",
            "xml" => "document",
            "ods" => "document",
            "xlr" => "document",
            "xls" => "document",
            "xlsx" => "document"
        );

        if (isset($file)) {
            $extension = strtolower($file->getClientOriginalExtension());
            $name = explode('.', $file->getClientOriginalName());

            if (isset($type[$extension])) {
                if ($disk == 'spaces') {
                    $filePath = '/' . $folder;
                    $spaceImage = Storage::disk('spaces')->put($filePath, $file, 'public');
                    return $spaceImage;
                }

                $upload = \App\Models\Media::updateOrCreate([
                    'file_original_name' => $name[0],
                    'type' => $type[$extension],
                    'file_size' => $file->getSize(),
                    'user_id' => Auth::check() ? request()->user()->id : 0,
                ], [
                    'file_original_name' => $name[0],
                    'type' => $type[$extension],
                    'file_name' => $file->store('/public'),
                    'user_id' => Auth::check() ? request()->user()->id : 0,
                    'file_size' => $file->getSize(),
                    'extension' => $extension,
                ]);

                return $upload->id;
            }
        }
        return null;
    }
}

if (!function_exists('getSpaceUrl')) {
    function getSpaceUrl($img)
    {
        return 'https://' . env('DO_SPACES_BUCKET') . '/' . $img;
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        $setting = Cache::remember('setting-' . $key, 86400, function () use ($key) {
            return \App\Models\Setting::where('key', $key)->first();
        });

        return $setting == null || empty($setting) ? $default : $setting->value;
    }
}

if (!function_exists('randomFromNumbers')) {
    function randomFromNumbers($times, $numbersArr)
    {
        if (count($numbersArr) > 0) {
            $random = [];
            for ($i = 0; $i < $times; $i++) {
                if ($i + 1 > count($numbersArr) && count($numbersArr) > 3) {
                    break;
                }
                $randKey = array_rand($numbersArr);
                $randNumber = $numbersArr[$randKey];
                if (($key = array_search($randNumber, $numbersArr)) !== false) {
                    unset($numbersArr[$key]);
                }
                $random[] = $randNumber;
            }
            return $random;
        }
        return [];
    }
}

if (!function_exists('getYoutubeEmbedUrl')) {
    function getYoutubeEmbedUrl($url)
    {
        $youtube_id = '';
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        $fullEmbedUrl = 'https://www.youtube.com/embed/' . $youtube_id;
        return $fullEmbedUrl;
    }
}

function strip_only($str, $tags, $stripContent = false)
{
    $content = '';
    if (!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if (end($tags) == '') {
            array_pop($tags);
        }
    }
    foreach ($tags as $tag) {
        if ($stripContent) {
            $content = '(.+</' . $tag . '[^>]*>|)';
        }
        $str = preg_replace('#</?' . $tag . '[^>]*>' . $content . '#is', '', $str);
    }
    return $str;
}

function number_category($number)
{
    if ($number <= 10) {
        return 'A';
    } elseif ($number <= 20) {
        return 'B';
    } elseif ($number <= 30) {
        return 'C';
    } elseif ($number <= 40) {
        return 'D';
    } elseif ($number <= 50) {
        return 'E';
    } elseif ($number <= 60) {
        return 'F';
    } elseif ($number <= 70) {
        return 'G';
    } elseif ($number <= 80) {
        return 'H';
    } elseif ($number <= 90) {
        return 'I';
    } elseif ($number <= 100) {
        return 'J';
    } elseif ($number <= 110) {
        return 'K';
    } elseif ($number <= 120) {
        return 'L';
    } elseif ($number <= 130) {
        return 'M';
    } elseif ($number <= 140) {
        return 'N';
    } elseif ($number <= 150) {
        return 'O';
    } elseif ($number <= 160) {
        return 'P';
    } elseif ($number <= 170) {
        return 'Q';
    } elseif ($number <= 180) {
        return 'R';
    } elseif ($number <= 190) {
        return 'S';
    } elseif ($number <= 200) {
        return 'T';
    } elseif ($number <= 210) {
        return 'U';
    } elseif ($number <= 220) {
        return 'V';
    } elseif ($number <= 230) {
        return 'W';
    } elseif ($number <= 240) {
        return 'X';
    } elseif ($number <= 250) {
        return 'Y';
    } elseif ($number <= 260) {
        return 'Z';
    }
    return '';
}

function encryptText($string, $encrypt = true)
{
    $secret_key = 'Cb9eGT2s#~';
    $secret_iv  = '3#t;fV._N[';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($encrypt) {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

if (!function_exists('apiResponse')) {
    function apiResponse($success = false, $data = null, $message = '', $errors = null, $code = 200, $version = 1)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'code'  => $code,
            'data'    => $data,
            'errors'  => $errors,
        ];
        return response()->json($response, $code);
    }
}

if (!function_exists('welcomeMessage')) {
    function welcomeMessage()
    {
        $time = date("H");
        $timezone = date("e");
        if ($time < "12") {
            return __('admin.good_morning');
        } elseif ($time >= "12" && $time < "17") {
            return __('admin.good_afternoon');
        } else {
            if ($time >= "17" && $time < "19") {
                return __('admin.good_evening');
            } elseif ($time >= "19") {
                return __('admin.good_night');
            }
        }
        return "";
    }
}

if (!function_exists('convertArabicNumbers')) {
    function convertArabicNumbers($string)
    {
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $num = range(0, 9);

        return str_replace($arabic, $num, $string);
    }
}
if (!function_exists('storeFile')) {
    function storeFile($image, $destination)
    {
        $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/'.$destination), $fileName);
        return $fileName;
    }
}
if (!function_exists('savedCompany')) {
    function  savedCompany($id)
    {

        if (auth()->check()) {
            return Saved::where('user_id', auth()->id())->where('model_id', $id)->where('model_type', Saved::TYPE_COMPANY)->first() ? 1 : 0;
        }
        return 0;

    }
}

function generateUserUniqueCode(): ?string
{
    $code = mt_rand(1, 1000000);
    if (User::where('code', $code)->exists()) {
        generateUserUniqueCode();
    }
    return $code;
}

function isMobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

  function convertToUnicode($message)
    {
        $chrArray[0] = "�";
        $unicodeArray[0] = "060C";
        $chrArray[1] = "�";
        $unicodeArray[1] = "061B";
        $chrArray[2] = "�";
        $unicodeArray[2] = "061F";
        $chrArray[3] = "�";
        $unicodeArray[3] = "0621";
        $chrArray[4] = "�";
        $unicodeArray[4] = "0622";
        $chrArray[5] = "�";
        $unicodeArray[5] = "0623";
        $chrArray[6] = "�";
        $unicodeArray[6] = "0624";
        $chrArray[7] = "�";
        $unicodeArray[7] = "0625";
        $chrArray[8] = "�";
        $unicodeArray[8] = "0626";
        $chrArray[9] = "�";
        $unicodeArray[9] = "0627";
        $chrArray[10] = "�";
        $unicodeArray[10] = "0628";
        $chrArray[11] = "�";
        $unicodeArray[11] = "0629";
        $chrArray[12] = "�";
        $unicodeArray[12] = "062A";
        $chrArray[13] = "�";
        $unicodeArray[13] = "062B";
        $chrArray[14] = "�";
        $unicodeArray[14] = "062C";
        $chrArray[15] = "�";
        $unicodeArray[15] = "062D";
        $chrArray[16] = "�";
        $unicodeArray[16] = "062E";
        $chrArray[17] = "�";
        $unicodeArray[17] = "062F";
        $chrArray[18] = "�";
        $unicodeArray[18] = "0630";
        $chrArray[19] = "�";
        $unicodeArray[19] = "0631";
        $chrArray[20] = "�";
        $unicodeArray[20] = "0632";
        $chrArray[21] = "�";
        $unicodeArray[21] = "0633";
        $chrArray[22] = "�";
        $unicodeArray[22] = "0634";
        $chrArray[23] = "�";
        $unicodeArray[23] = "0635";
        $chrArray[24] = "�";
        $unicodeArray[24] = "0636";
        $chrArray[25] = "�";
        $unicodeArray[25] = "0637";
        $chrArray[26] = "�";
        $unicodeArray[26] = "0638";
        $chrArray[27] = "�";
        $unicodeArray[27] = "0639";
        $chrArray[28] = "�";
        $unicodeArray[28] = "063A";
        $chrArray[29] = "�";
        $unicodeArray[29] = "0641";
        $chrArray[30] = "�";
        $unicodeArray[30] = "0642";
        $chrArray[31] = "�";
        $unicodeArray[31] = "0643";
        $chrArray[32] = "�";
        $unicodeArray[32] = "0644";
        $chrArray[33] = "�";
        $unicodeArray[33] = "0645";
        $chrArray[34] = "�";
        $unicodeArray[34] = "0646";
        $chrArray[35] = "�";
        $unicodeArray[35] = "0647";
        $chrArray[36] = "�";
        $unicodeArray[36] = "0648";
        $chrArray[37] = "�";
        $unicodeArray[37] = "0649";
        $chrArray[38] = "�";
        $unicodeArray[38] = "064A";
        $chrArray[39] = "�";
        $unicodeArray[39] = "0640";
        $chrArray[40] = "�";
        $unicodeArray[40] = "064B";
        $chrArray[41] = "�";
        $unicodeArray[41] = "064C";
        $chrArray[42] = "�";
        $unicodeArray[42] = "064D";
        $chrArray[43] = "�";
        $unicodeArray[43] = "064E";
        $chrArray[44] = "�";
        $unicodeArray[44] = "064F";
        $chrArray[45] = "�";
        $unicodeArray[45] = "0650";
        $chrArray[46] = "�";
        $unicodeArray[46] = "0651";
        $chrArray[47] = "�";
        $unicodeArray[47] = "0652";
        $chrArray[48] = "!";
        $unicodeArray[48] = "0021";
        $chrArray[49]='"';
        $unicodeArray[49] = "0022";
        $chrArray[50] = "#";
        $unicodeArray[50] = "0023";
        $chrArray[51] = "$";
        $unicodeArray[51] = "0024";
        $chrArray[52] = "%";
        $unicodeArray[52] = "0025";
        $chrArray[53] = "&";
        $unicodeArray[53] = "0026";
        $chrArray[54] = "'";
        $unicodeArray[54] = "0027";
        $chrArray[55] = "(";
        $unicodeArray[55] = "0028";
        $chrArray[56] = ")";
        $unicodeArray[56] = "0029";
        $chrArray[57] = "*";
        $unicodeArray[57] = "002A";
        $chrArray[58] = "+";
        $unicodeArray[58] = "002B";
        $chrArray[59] = ",";
        $unicodeArray[59] = "002C";
        $chrArray[60] = "-";
        $unicodeArray[60] = "002D";
        $chrArray[61] = ".";
        $unicodeArray[61] = "002E";
        $chrArray[62] = "/";
        $unicodeArray[62] = "002F";
        $chrArray[63] = "0";
        $unicodeArray[63] = "0030";
        $chrArray[64] = "1";
        $unicodeArray[64] = "0031";
        $chrArray[65] = "2";
        $unicodeArray[65] = "0032";
        $chrArray[66] = "3";
        $unicodeArray[66] = "0033";
        $chrArray[67] = "4";
        $unicodeArray[67] = "0034";
        $chrArray[68] = "5";
        $unicodeArray[68] = "0035";
        $chrArray[69] = "6";
        $unicodeArray[69] = "0036";
        $chrArray[70] = "7";
        $unicodeArray[70] = "0037";
        $chrArray[71] = "8";
        $unicodeArray[71] = "0038";
        $chrArray[72] = "9";
        $unicodeArray[72] = "0039";
        $chrArray[73] = ":";
        $unicodeArray[73] = "003A";
        $chrArray[74] = ";";
        $unicodeArray[74] = "003B";
        $chrArray[75] = "<";
        $unicodeArray[75] = "003C";
        $chrArray[76] = "=";
        $unicodeArray[76] = "003D";
        $chrArray[77] = ">";
        $unicodeArray[77] = "003E";
        $chrArray[78] = "?";
        $unicodeArray[78] = "003F";
        $chrArray[79] = "@";
        $unicodeArray[79] = "0040";
        $chrArray[80] = "A";
        $unicodeArray[80] = "0041";
        $chrArray[81] = "B";
        $unicodeArray[81] = "0042";
        $chrArray[82] = "C";
        $unicodeArray[82] = "0043";
        $chrArray[83] = "D";
        $unicodeArray[83] = "0044";
        $chrArray[84] = "E";
        $unicodeArray[84] = "0045";
        $chrArray[85] = "F";
        $unicodeArray[85] = "0046";
        $chrArray[86] = "G";
        $unicodeArray[86] = "0047";
        $chrArray[87] = "H";
        $unicodeArray[87] = "0048";
        $chrArray[88] = "I";
        $unicodeArray[88] = "0049";
        $chrArray[89] = "J";
        $unicodeArray[89] = "004A";
        $chrArray[90] = "K";
        $unicodeArray[90] = "004B";
        $chrArray[91] = "L";
        $unicodeArray[91] = "004C";
        $chrArray[92] = "M";
        $unicodeArray[92] = "004D";
        $chrArray[93] = "N";
        $unicodeArray[93] = "004E";
        $chrArray[94] = "O";
        $unicodeArray[94] = "004F";
        $chrArray[95] = "P";
        $unicodeArray[95] = "0050";
        $chrArray[96] = "Q";
        $unicodeArray[96] = "0051";
        $chrArray[97] = "R";
        $unicodeArray[97] = "0052";
        $chrArray[98] = "S";
        $unicodeArray[98] = "0053";
        $chrArray[99] = "T";
        $unicodeArray[99] = "0054";
        $chrArray[100] = "U";
        $unicodeArray[100] = "0055";
        $chrArray[101] = "V";
        $unicodeArray[101] = "0056";
        $chrArray[102] = "W";
        $unicodeArray[102] = "0057";
        $chrArray[103] = "X";
        $unicodeArray[103] = "0058";
        $chrArray[104] = "Y";
        $unicodeArray[104] = "0059";
        $chrArray[105] = "Z";
        $unicodeArray[105] = "005A";
        $chrArray[106] = "[";
        $unicodeArray[106] = "005B";
        $char="\ ";
        $chrArray[107]=trim($char);
        $unicodeArray[107] = "005C";
        $chrArray[108] = "]";
        $unicodeArray[108] = "005D";
        $chrArray[109] = "^";
        $unicodeArray[109] = "005E";
        $chrArray[110] = "_";
        $unicodeArray[110] = "005F";
        $chrArray[111] = "`";
        $unicodeArray[111] = "0060";
        $chrArray[112] = "a";
        $unicodeArray[112] = "0061";
        $chrArray[113] = "b";
        $unicodeArray[113] = "0062";
        $chrArray[114] = "c";
        $unicodeArray[114] = "0063";
        $chrArray[115] = "d";
        $unicodeArray[115] = "0064";
        $chrArray[116] = "e";
        $unicodeArray[116] = "0065";
        $chrArray[117] = "f";
        $unicodeArray[117] = "0066";
        $chrArray[118] = "g";
        $unicodeArray[118] = "0067";
        $chrArray[119] = "h";
        $unicodeArray[119] = "0068";
        $chrArray[120] = "i";
        $unicodeArray[120] = "0069";
        $chrArray[121] = "j";
        $unicodeArray[121] = "006A";
        $chrArray[122] = "k";
        $unicodeArray[122] = "006B";
        $chrArray[123] = "l";
        $unicodeArray[123] = "006C";
        $chrArray[124] = "m";
        $unicodeArray[124] = "006D";
        $chrArray[125] = "n";
        $unicodeArray[125] = "006E";
        $chrArray[126] = "o";
        $unicodeArray[126] = "006F";
        $chrArray[127] = "p";
        $unicodeArray[127] = "0070";
        $chrArray[128] = "q";
        $unicodeArray[128] = "0071";
        $chrArray[129] = "r";
        $unicodeArray[129] = "0072";
        $chrArray[130] = "s";
        $unicodeArray[130] = "0073";
        $chrArray[131] = "t";
        $unicodeArray[131] = "0074";
        $chrArray[132] = "u";
        $unicodeArray[132] = "0075";
        $chrArray[133] = "v";
        $unicodeArray[133] = "0076";
        $chrArray[134] = "w";
        $unicodeArray[134] = "0077";
        $chrArray[135] = "x";
        $unicodeArray[135] = "0078";
        $chrArray[136] = "y";
        $unicodeArray[136] = "0079";
        $chrArray[137] = "z";
        $unicodeArray[137] = "007A";
        $chrArray[138] = "{";
        $unicodeArray[138] = "007B";
        $chrArray[139] = "|";
        $unicodeArray[139] = "007C";
        $chrArray[140] = "}";
        $unicodeArray[140] = "007D";
        $chrArray[141] = "~";
        $unicodeArray[141] = "007E";
        $chrArray[142] = "�";
        $unicodeArray[142] = "00A9";
        $chrArray[143] = "�";
        $unicodeArray[143] = "00AE";
        $chrArray[144] = "�";
        $unicodeArray[144] = "00F7";
        $chrArray[145] = "�";
        $unicodeArray[145] = "00F7";
        $chrArray[146] = "�";
        $unicodeArray[146] = "00A7";
        $chrArray[147] = " ";
        $unicodeArray[147] = "0020";
        $chrArray[148] = "\n";
        $unicodeArray[148] = "000D";
        $chrArray[149] = "\r";
        $unicodeArray[149] = "000A";

        $strResult = "";
        for($i=0; $i<strlen($message); $i++)
        {
            if(in_array(substr($message,$i,1), $chrArray))
            $strResult.= $unicodeArray[array_search(substr($message,$i,1), $chrArray)];
        }
        return $strResult;
    }
