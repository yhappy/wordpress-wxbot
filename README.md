---
highlight: a11y-dark
---
网上的实现 Wordpress 评论通知的方法一般有两种：

一种是通过 server 酱，网上介绍的很多，[例如： Wordpress实现评论微信通知！](https://juejin.cn/post/6987325221258657828)

推荐使用另一种，是通过企业微信群聊机器人来通知，最关键的获取机器人的 webhook，详细可见文档：
[企业微信群机器人配置说明](https://developer.work.weixin.qq.com/document/path/91770)

但是互联网上有的文章里面的代码过于久，没办法直接用，下面是经过尝试，最新 Wordpress 可以使用的代码：


``` php
function commit_send_wx($comment_id) {
    $comment = get_comment($comment_id);
    // 企业微信机器人webHook地址
    $endpoint = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=#############';
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
```

如果有新通知来，效果如下：

![wechat_bot](https://p3-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/ec8579cdafc045419d63e51834cf8de1~tplv-k3u1fbpfcp-zoom-1.image)


使用Server酱通知的方式，虽然选择性多，但存在服务安全和稳定性问题，同时需要考虑会员问题。而使用自己的企业微信进行通知，则不需要考虑服务不可用和通知频率限制的问题。