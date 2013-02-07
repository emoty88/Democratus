<?php
    class export_plugin extends control{
        
        public function main(){
            global $model, $db;
            $model->mode = 0;
            
            
            if($model->useris('superadmin')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            $SELECT     = "SELECT *";
            $FROM       = "\n FROM agenda AS a, agendavote AS av, profile AS p";
            $WHERE      = "\n WHERE av.agendaID = a.ID";
            $WHERE     .= "\n AND p.ID = av.profileID";
            $ORDER      = "\n ORDER BY a.ID";
            $LIMIT      = "\n LIMIT 350";
            
            $db->setQuery($SELECT.$FROM.$WHERE.$ORDER.$LIMIT);
            $rows = $db->loadAssocList();
            
            if(count($rows)){
                echo '<table border="1">';
                
                
                $i = 0;
                foreach($rows as $key=>$row){
                    if($i==0){
                        $a = array_keys($row);
                        echo '<tr>';
                        foreach($a as $b) echo '<td>'.$b.'</td>';
                        echo '</tr>';
                    }
                    
                    $a = array_keys($row);
                    echo '<tr>';
                    foreach($a as $b) echo '<td>'.$row[$b].'&nbsp;</td>';
                    echo '</tr>';
                    
                    ++$i;
                    
                }
                echo '</table>';
                
                
            } else {
                die('not found');
            }
            
            
                        
        }
    }
?>