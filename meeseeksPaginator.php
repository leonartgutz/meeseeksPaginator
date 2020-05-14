<?php

    class meeseeksPaginator{

        private $total;
        private $show_per_page;
        public $first_title = 'First';
        public $back_title = 'Back';
        public $next_title = 'Next';
        public $last_title = 'Last';
        public $page_title = '';
        private $current_pg;

        function __construct($total, $show_per_page){

            $this->total = $total;
            $this->show_per_page = $show_per_page;

        }

        function createPages($show_pages = true){           

            if($this->total > 0){

                $http = 'http';

                if(isset($_SERVER['HTTPS'])){

                    if($_SERVER['HTTPS'] == 'on'){
                        $http = 'https';
                    }
                }
                
                $url_build = array();

                $numPg = ceil($this->total/$this->show_per_page);
                $url_scheme = $http . '://';
                $url_host = $_SERVER['HTTP_HOST'];
                $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $url_parameters = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

                if(!empty($url_parameters)){
                    
                    $url_explode = explode("&", $url_parameters);
                    $search_parameter_pg = preg_quote('pg=', '~');
                    $get_key_parameter_pg = preg_grep('~'.$search_parameter_pg.'~', $url_explode);

                    if(count($get_key_parameter_pg) == 1){

                        $key_pg_found = key($get_key_parameter_pg);
                        $explode_key_parameter_pg = explode("=", $url_explode[$key_pg_found]);
                        $current_pg = $explode_key_parameter_pg[1];
                        unset($url_explode[$key_pg_found]);
                        $url_explode = array_values($url_explode);

                    }elseif(count($get_key_parameter_pg) == 0){               
                        
                        $current_pg = 1;

                    }else{
                        exit('Existem parametros duplicados na URL');
                    }

                    for($i = 0; $i < count($url_explode); $i++){

                        $new_array_parameters = explode('=', $url_explode[$i]);
                        $new_array_key = $new_array_parameters[0];
                        $new_array_value = $new_array_parameters[1];
                        $url_build[$new_array_key] = $new_array_value;

                    }

                }else{

                    $current_pg = 1;

                }

                $result = '';

                if($this->first_title){

                    if($current_pg != 1){

                        $url_build['pg'] = 1;
                        $url_query = http_build_query($url_build);

                        $result .= '<a href="' . $url_scheme . $url_host . $url_path . '?' . $url_query . '">' . $this->first_title . '</a>';

                    }else{

                        $result .= '<i class="active-pg">' .  $this->first_title . '</i>';

                    }

                }

                if($this->back_title){

                    if(($current_pg - 1) > 0){

                        $url_build['pg'] = $current_pg - 1;
                        $url_query = http_build_query($url_build);

                        $result .= '<a href="' . $url_scheme . $url_host . $url_path . '?' . $url_query . '">' . $this->back_title . '</a>';

                    }else{

                        $result .= '<i class="active-pg">' .  $this->back_title . '</i>';

                    }

                }

                if($show_pages === true){

                    for($a = 2; $a >= 1; $a--){

                        if(($current_pg - $a) > 0){

                            $url_build['pg'] = $current_pg - $a;
                            $url_query = http_build_query($url_build);
                            
                            $result .= '<a href="' . $url_scheme . $url_host . $url_path . '?' . $url_query . '">' . $this->page_title . ($current_pg - $a) . '</a>';

                        }

                    }

                    $url_build['pg'] = $current_pg;
                    $url_query = http_build_query($url_build);

                    $result .= '<i class="active-pg">' .  $this->page_title . ($current_pg - $a) . '</i>';

                    for($a = 1; $a <= 2; $a++){

                        if(($current_pg + $a) <= $numPg){

                            $url_build['pg'] = $current_pg + $a;
                            $url_query = http_build_query($url_build);
                            
                            $result .= '<a href="' . $url_scheme . $url_host . $url_path . '?' . $url_query . '">' .  $this->page_title . ($current_pg + $a) . '</a>';

                        }

                    }

                }

                if($this->next_title){

                    if(($current_pg + 1) <= $numPg){

                        $url_build['pg'] = $current_pg + 1;
                        $url_query = http_build_query($url_build);

                        $result .= '<a href="' . $url_scheme . $url_host . $url_path . '?' . $url_query . '">' . $this->next_title . '</a>';
                        
                    }else{

                        $result .= '<i class="active-pg">' .  $this->next_title . '</i>';

                    }

                }

                if($this->last_title){

                    if($current_pg != $numPg){

                        $url_build['pg'] = $numPg;
                        $url_query = http_build_query($url_build);

                        $result .= '<a href="' . $url_scheme . $url_host . $url_path . '?' . $url_query . '">' . $this->last_title . '</a>';

                    }else{

                        $result .= '<i class="active-pg">' .  $this->last_title . '</i>';

                    }

                }

            }else{

                echo '';

            }

            echo $result;

        }       

        function getCurrentPg(){

            $url_parameters = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

            if(!empty($url_parameters)){
                
                $url_explode = explode("&", $url_parameters);
                $search_parameter_pg = preg_quote('pg=', '~');
                $get_key_parameter_pg = preg_grep('~'.$search_parameter_pg.'~', $url_explode);

                if(count($get_key_parameter_pg) == 1){

                    $key_pg_found = key($get_key_parameter_pg);
                    $explode_key_parameter_pg = explode("=", $url_explode[$key_pg_found]);
                    $this->current_pg = $explode_key_parameter_pg[1];

                }elseif(count($get_key_parameter_pg) == 0){
                    
                    $this->current_pg = 1;

                }else{
                    exit('Existem parametros duplicados na URL');
                }

                    for($i = 0; $i < count($url_explode); $i++){

                        $new_array_parameters = explode('=', $url_explode[$i]);
                        $new_array_key = $new_array_parameters[0];
                        $new_array_value = $new_array_parameters[1];
                        $url_build[$new_array_key] = $new_array_value;

                    }

                }else{

                    $this->current_pg = 1;

                }

            return $this->current_pg;
            
        }

    }

?>