<?php

if (!isset($_REQUEST)) {
    return;
}

//база

$blt = array("хуй", "пизда", "пидор", "говно", "ублюдок");
$prv = array("привет", "здравствуй", "прив", "хало", "даров","привки");
$neg_ansv = array("пиздец","плохо", "ужасно", "просто жопа");
$confirmationToken = 'возврат';


$token = 'токен';


$secretKey = 'ключ';


$data = json_decode(file_get_contents('php://input'));


if(strcmp($data->secret, $secretKey) !== 0 && strcmp($data->type, 'confirmation') !== 0)
    return;

switch ($data->type) {

    case 'confirmation':

        echo $confirmationToken;
        break;


    case 'message_new':

        $userId = $data->object->user_id;
        $users_message = $data->object->body;

        $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$userId}&access_token={$token}&v=5.8"));

        $user_name = $userInfo->response[0]->first_name;
        $tipical = "{$user_name}, ты прости дурака. Не понял я, что ты сказал. Но в базу записал и скоро научусь отвечать нормально";
        $fin_msg = $tipical;

       

        if($fin_msg == $tipical) {
            foreach ($blt as $value) {
                if (strripos($users_message, $value) == true || mb_strtolower($users_message) == $value) {
                    $fin_msg = "{$user_name}, давайте ка по вежливее, а то смею напомнить, что на боку моего пистолета написано Desert Eagle 50, а вашего пистолета я не вижу";
                }
            }
        }
        if($fin_msg == $tipical){
            foreach ($prv as $value) {
                if (strripos($users_message, $value) == true || mb_strtolower($users_message) == $value) {
                    $fin_msg = "Ну что {$user_name}, как оно?";
                }
            }

        }
        if($fin_msg == $tipical){
            foreach ($neg_ansv as $value) {
                if (strripos($users_message, $value) == true || mb_strtolower($users_message) == $value) {
                    $fin_msg = "Блин ну хреново быть тобой, {$user_name}, что тут еще скажешь";
                }
            }

        }



                $request_params = array(
                    'message' => $fin_msg,
                    'user_id' => $userId,
                    'access_token' => $token,
                    'v' => '5.8'
                );

                $get_params = http_build_query($request_params);

                file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);


                echo('ok');

        break;







    case 'group_join':

        $userId = $data->object->user_id;


        $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$userId}&access_token={$token}&v=5.8"));

        //и извлекаем из ответа его имя
        $user_name = $userInfo->response[0]->first_name;









        $request_params = array(
            'message' => "{$user_name} привет. Че ты тут забыл?",
            'user_id' => $userId,
            'access_token' => $token,
            'v' => '5.8'
        );

        $get_params = http_build_query($request_params);

        file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);


        echo('ok');

        break;
}
?>
