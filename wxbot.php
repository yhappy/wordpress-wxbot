//  两种方法只能选一种！！！
//  企业微信
function commit_send_wx($comment_id) {
    $comment = get_comment($comment_id);
    // 企业微信机器人webHook地址
    $endpoint = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=###################';
    // 评论内容
    $cont = $comment->comment_content;
    // 评论人昵称
    $title = $comment->comment_author;
    // 文本消息
    $body = array(
        "msgtype" => "text",
        "text"    => array(
            "content" => $cont,
            "mentioned_list" => array("@all")
        )
    );
    $body = wp_json_encode($body);
    $options = [
        'body'           => $body,
        'headers'        => [
          'Content-Type' => 'application/json',
        ],
        'data_format'    => 'body',
    ];
    wp_remote_post( $endpoint, $options );
}
add_action('comment_post', 'commit_send_wx', 19, 2);


//  server酱
function commit_send($comment_id)  {  
    $comment = get_comment($comment_id);  
    $text = '@'.$comment->comment_author.'发来了一条评论';  
    $desp = $comment->comment_content;  
    $key = '############################';  
    $postdata = http_build_query(  
        array(  
            'text' => $text,  
            'desp' => $desp  
        )  
     );  
    
    $opts = array('http' =>  
        array(  
            'method' => 'POST',  
            'header' => 'Content-type: application/x-www-form-urlencoded',  
            'content' => $postdata  
        )  
    );  
    $context = stream_context_create($opts);  
    return $result = file_get_contents('https://sctapi.ftqq.com/'.$key.'.send', false, $context);  
}  
add_action('comment_post', 'commit_send', 19, 2);



