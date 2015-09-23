<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/19
 * Time: 13:56
 * 目标：对百度的新闻进行爬取，为了减轻难度，只针对百度新闻首页的新闻
 * 步骤：取得百度新闻首页的内容，匹配其中的链接，得到链接后，进一步取得这些链接的内容，然后结束，因为内容比较少，
 * 通过文件来存储，就不保存在数据库中了
 */

class crowbug {
    public $target = "http://news.baidu.com";//目标
    public $source = '';
    public function getFirstLink(){//取得首页的link,为什么将结果保存在数组中结果就会为空呢？
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->target);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
        $html = curl_exec($ch);
        //$http_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        //curl_close($ch);
        $this->source =  $html;
        return $this;
    }
    public function filterFirst()
    {//过滤首页链接，调用次级链接
        $hrefs = array();
        $preg = '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/i';
        preg_match_all($preg, $this->source, $match);//取得页面中的所有链接
     foreach ($match as $k => $v) {//1.取得href的内容，2取得content的内容
            foreach ($v as $key => $value) {
                $pos = strstr($value, 'http');
                if (!$pos) {
                    continue;
                } else {
                    $href = '';
                    $i = 0;
                    while ($pos[$i] != '"') {
                        $href .= $pos[$i];
                        $i++;
                    }
                    $hrefs[] = $href;
                }
            }
         if(count($hrefs)>1000){
             break;
         }
     }
        var_dump($hrefs);
    }
    public function getSecondLink(){//取得次级连接

    }
    public function Page($name,$content){//保存文件的内容,保存在page中

    }
}
set_time_limit(0);
$a = new crowbug();
$a->getFirstLink()->filterFirst();

