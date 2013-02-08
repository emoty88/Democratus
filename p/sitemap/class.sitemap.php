<?php
    class sitemap_plugin extends control{
        
        public function main(){
            global $model, $db;
            $model->mode = 0;
            $sm = $model->paths[1];
            if(preg_match('/profiles-(\d+)/i', $sm, $m ))
                $this->profile(intval($m[1]));
            elseif(preg_match('/dies-(\d+)/i', $sm, $m ))
                $this->di(intval($m[1]));
            else 
                $this->sitemapindex();
            
            if(!headers_sent()) header ("content-type: text/xml");
        }

        public function profile($start){
            global $model, $db;
            
            if($start<0) $start = -1 * $start;
            
            $end    = ( $start + 1) * 10000;
            $start  = $start * 10000;
            
            $db->setQuery("SELECT profile.ID, user.registertime AS datetime FROM profile, user WHERE profile.ID>=$start AND profile.ID<$end AND user.ID=profile.ID AND profile.status>0");
            $rows = $db->loadObjectList();
            if(count($rows)){
                
                echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
                echo '<?xml-stylesheet type="text/xsl" href="'.$model->pluginurl.'sitemap.xsl"?>'."\n";
                echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                
                
                foreach($rows as $row){
                    echo '<url>';
                    echo '<loc>'.$model->siteurl . 'profile/' . $row->ID.'</loc>';
                    echo '<lastmod>'.date('Y-m-d',strtotime($row->datetime)).'</lastmod>';
                    echo '<changefreq>weekly</changefreq>';
                    echo '<priority>0.8</priority>';
                    echo '</url>'."\n"; 
                }
                
                echo '</urlset>';
            }
            
            
            
        }        
    }
?>
