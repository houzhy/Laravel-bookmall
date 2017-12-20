<?php

namespace App\Models;

class M3Email {

  public $from;  // 发件人邮箱
  public $to; // 收件人邮箱
  public $cc; // 抄送(发送给其他人,数组可以发送多人)
  public $attach; // 附件
  public $subject; // 主题
  public $content; // 内容

}
