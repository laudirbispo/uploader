<?php declare(strict_types=1);
namespace libs\Encryption;

class AES {
	
	const DEFAULT_KEY = '';
	const DEFAULT_MODE = 'CBC';
	const DEFAULT_BLOCK_SIZE = 256;
   
    protected $key;
    protected $method;
	
    /**
     * Available OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
     *
     * @var type $options
     */
    protected $options = 0;
    /**
     * 
     * @param type $data
     * @param type $key
     * @param type $blockSize
     * @param type $mode
     */
    function __construct ($key = self::DEFAULT_KEY, $blockSize = self::DEFAULT_BLOCK_SIZE, $mode = 'CBC') 
	{
        $this->key = $key;
        $this->setMethode($blockSize, $mode);
    }
	
    /**
     * 
     * @param type $key
     */
    public function setKey (string $key) : self
	{
        $this->key = $key;
		return $this;
    }
    /**
     * CBC 128 192 256 
     * CBC-HMAC-SHA1 128 256
     * CBC-HMAC-SHA256 128 256
     * CFB 128 192 256
     * CFB1 128 192 256
     * CFB8 128 192 256
     * CTR 128 192 256
     * ECB 128 192 256
     * OFB 128 192 256
     * XTS 128 256
     * @param type $blockSize
     * @param type $mode
     */
    public function setMethode ($blockSize = self::DEFAULT_BLOCK_SIZE, $mode = self::DEFAULT_MODE) : self
	{
        if ($blockSize == 192 && in_array('', array('CBC-HMAC-SHA1','CBC-HMAC-SHA256','XTS')))
		{
        	$this->method = null;
            throw new \EncryptException('Combinação inválida de tamanho e modo de bloco!');
        }
        $this->method = 'AES-' . $blockSize . '-' . $mode;
		return $this;
    }

	//it must be the same when you encrypt and decrypt
    protected function getIV () 
	{
         return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
    }
	
    /**
     * @return type
     * @throws Exception
     */
    public function encrypt ($data) 
	{
		$crypt = openssl_encrypt($data, $this->method, $this->key, $this->options,$this->getIV());
		if (!$crypt)
			throw new \EncryptException('Não foi possível criptografar os dados!');
		else
    		return trim($crypt);
    }
	
    /**
     * 
     * @return type
     * @throws Exception
     */
    public function decrypt ($data) 
	{
    	$ret = openssl_decrypt($this->data, $this->method, $this->key, $this->options,$this->getIV());
          
           return   trim($ret); 
        } else {
            throw new Exception('Invlid params!');
        }
    }
}