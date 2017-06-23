<?php


trait Reaction
{
    /**
     * @param   string  $post_id
     * @param   string  $type
     * @return  string
     */
    public function reaction($post_id, $type="LIKE")
    {
        /**
         *  Valid reaction
         */
        $type = strtoupper($type);
        $selector = array('LIKE' => 0, 'LOVE' => 1, 'HAHA' => 2, 'WOW' => 3, 'SAD' => 4, 'ANGRY' => 5);
        $urlto = (substr($post_id, 0, 8)=="https://" or substr($post_id, 0, 7)=="http://") ? $post_id : self::FBURL.'/'.$post_id;
        if (isset($selector[$type])) {        
            $a = explode('<a href=', $this->get_reaction_list($this->curl($urlto, null, array(52=>true))));
            $reaction_list = array();
            for ($i=1; $i < count($a)-1; $i++) {
                $b = explode('"', $a[$i], 2);
                $b = explode('"', $b[1], 2);
                $reaction_list[] = html_entity_decode($b[0], ENT_QUOTES, 'UTF-8');
            }
            return $this->curl(substr($reaction_list[$selector[$type]], 1), null, array(52=>true,CURLOPT_REFERER=>$this->curl_info['url']));
        } else {
            throw new \Exception("Invalid Reaction ! \n\nAllowed reactions : (LIKE,LOVE,HAHA,WOW,SAD,ANGRY)\n\n", 1);
        }
    }

    /**
     * @param   string  $source
     * @return  string
     */
    private function get_reaction_list($source)
    {
        $a = explode('href="/reactions/picker/?', $source, 2);
        $a = explode('"', $a[1], 2);
        return $this->curl('reactions/picker/?'.html_entity_decode($a[0], ENT_QUOTES, 'UTF-8'), null, array(CURLOPT_REFERER=>$this->curl_info['url']));
    }
}
