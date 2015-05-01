<?php

class AtomLegilo{

    public $url;
    public $prinskribo_grando;
    public $elemento_nombro;
    protected $laste_komentis;


    function __construct($fluourl){

            
        $this->url = $fluourl;


        $this->priskribo_grando = 100;
        $this->elemento_nombro = 7;


        /** 
        * Kiam oni lastfoje afiŝis pepon? La unuan fojon oni uzas 
        * gsfluon, ĝi donas la valoron '' al la variablo $laste_afishis
        **/        
        if (!(get_post_meta( get_the_ID(), 'wpgs_laste_komentis', true ) == '')) {
            
            $this->laste_komentis = date_create(get_post_meta( get_the_ID(), 'wpgs_laste_komentis', true ));
            
        }else{

            $this->laste_komentis = date_create('01-01-1970');
        }

    }


    function ghisdatigi_daton($afish_id) {

        update_post_meta( $afish_id, 'wpgs_laste_komentis', $this->laste_komentis->format('Y-m-d H:i:s'));
    }


    /**
     * Legas la rssfluon indikitan de la uzanto, kontrolas ĉu estas novaj elementoj
     * kaj revenigas tabelon el novaj elementoj kreitaj laŭ la klaso Elemento
     *
    **/
    function legi($afish_id) {

        $afish_id = $afish_id;
        
        $fluo = simplexml_load_file($this->url);

        $n = 0;

        $elementoj = array();

        // Iteracia kontrolo de ĉiuj ricevitaj elementoj
        foreach($fluo->entry as $ero){

            if($ero->author->children('poco', true)->displayName !=NULL && $ero->content!='' && $n< $this->elemento_nombro){

                $elemento = new Elemento($afish_id, $ero->author->children('poco', true)->displayName, $ero->author->uri, $ero->author->name, $ero->author->link[1]->attributes()->href, $ero->content, date_create($ero->published));

                //$elemento->aranghi_kategoriojn($ero->category);

                array_push($elementoj, $elemento);
            }
	        $n++; 
        }
        
        $elementoj = array_reverse($elementoj);
        $novaj_elementoj = array();     

        
        foreach($elementoj as $elemento){
        
            // la unuan fojon oni rulas gsfluon laste_afishis egalas al 01-01-1970
            
            //Ĉu la elemento estas nova?
            if ($elemento->novas($this->laste_komentis)) {
                
                // Aldonado de la elemento al la revenigota tabelo
                array_push($novaj_elementoj, $elemento);

                // Ĝisdatigo de la dato kiu estos konservota kiel dato por lasta afiŝo
                $this->laste_komentis = $elemento->publikig_dato;
            }
        }
        
        return $novaj_elementoj;
    }

}


class Elemento {

    public $afisho_id;
    public $auhtoro;
    public $auhtoro_url;
    public $auhtoro_salutnomo;
    public $enhavo;
    public $tipo;
    public $patro;
    public $publikig_dato;
    public $avataro;

    function __construct($afisho_id, $auhtoro, $auhtoro_url, $auhtoro_salutnomo, $avataro, $enhavo, $dato) {
        
        $this->afisho_id = $afisho_id;
        $this->auhtoro = $auhtoro;
        $this->auhtoro_url = $auhtoro_url;
        $this->auhtoro_salutnomo = $auhtoro_salutnomo;
        $this->enhavo = $enhavo;
        $this->publikig_dato = $dato;
        $this->avataro = $avataro;
    }

    /**
     * Kontrolas ĉu la elemento estas nova kompare al provizita dato
     * 
     *@param $dato kiu devas aparteni al la datumtipo date
    **/
    function novas($dato){

        if ($this->publikig_dato > $dato) {
            return True;
        }else{
            return False;
        }
    }

    /**
     * Aranĝas kategoriojn laŭ la formo #kategorio1 #kategorio2 #kategorio3
     * 
     *@param $dato kiu devas aparteni al la datumtipo date
    **/
    function aranghi_kategoriojn($kategorioj) {

        foreach ($kategorioj as $kategorio) {
            $this->kategorioj .= '#' . str_replace(' ', '', $kategorio) . ' ';
        }
    }
}


class GsKonektilo {

    public $api_url;
    public $salutnomo;
    public $pasvorto;

    function __construct($api_url, $salutnomo, $pasvorto) {
        
        $this->api_url = $api_url;
        $this->salutnomo = $salutnomo;
        $this->pasvorto = $pasvorto;

    }

    function afishi($titolo, $url, $priskribo, $kategorioj ) {
    
        $headers = array( 'Authorization' => 'Basic '.base64_encode("$this->salutnomo:$this->pasvorto") );
        
        $message = $titolo . " " . $url  . " " . $priskribo . " ". $kategorioj;
        
        $body = array( 'status' => $message );
        
        $response = wp_remote_post( $this->api_url, array(
	        'method' => 'POST',
	        'timeout' => 45,
	        'redirection' => 5,
	        'httpversion' => '1.0',
	        'blocking' => true,
	        'headers' => $headers,
	        'body' => $body,
	        'cookies' => array()
            )
        );
        
        if ( is_wp_error( $response ) ) {
           $error_message = $response->get_error_message();
           echo "Something went wrong: $error_message";
        } else {
           return $response;
        }
        
    }
}
