<?php
    header("content-type;text/html;charset=utf-8");
    $article_title=$_POST['article_title'];
    $url=$_POST['website_url'];
    //var_dump($article_title,$url);
    
 function get_html($url){
    
        $ch = curl_init();   
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36');
   
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
 
        return $html;
        
  }
  $html=get_html($url);
  
  //echo $html;
  
  $coding = mb_detect_encoding($html);
  if ($coding != "UTF-8" || !mb_check_encoding($html, "UTF-8"))
      
      $html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
  
  
  $pattern = '|<a[^>]*>(.*)</a>|isU';
  preg_match_all($pattern, $html, $matches);
  
  $a=$matches[0][0];
  
  $dom = new DOMDocument();
      
  @$dom->loadHTML($a);//$a是上面得到的一些a标签
      
  $url = new DOMXPath($dom);
      
  $hrefs = $url->evaluate('//a');
      
  for ($i = 0; $i < $hrefs->length; $i++) {
      
  $href = $hrefs->item($i);
      
  $url = $href->getAttribute('href'); //这里获取a标签的href属性
      
  }



    var website_url = 'home.html';
    $.getJSON(website_url,function(data){
     if(data){
      if(data.text == ''){
       $('#article_url').html('<div><p>暂无该文章链接</p></div>');
       return;
      }
      var string = '';
      var list = data.text;
      for (var j in list) {
        var content = list[j].url_content;
        for (var i in content) {
         if (content[i].title != '') {
          string += '<div class="item">' +
           '<em>[<a href="http://' + list[j].website.web_url + '" target="_blank">' + list[j].website.web_name + '</a>]</em>' +
           '<a href=" ' + content[i].url + '" target="_blank" class="web_url">' + content[i].title + '</a>' +
           '</div>';
         }
        }
       }
      $('#article_url').html(string);
    });
  
 ?>
