<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Services;
/**
 * @author - Laudir Bispo, laudirbispo@outlook.com
 *
 * AVISO DE LICENÇA
 * 
 * @license - Em hipótese alguma é permitido ao LICENCIADO ou a terceiros, de forma geral:
 * Copiar, ceder, sublicenciar, vender, dar em locação ou em garantia, reproduzir, doar, 
 * alienar de qualquer forma, transferir total ou parcialmente, sob quaisquer modalidades, gratuita ou onerosamente, 
 * provisória ou permanentemente, o SOFTWARE objeto deste EULA, assim como seus módulos, partes,  
 * manuais ou quaisquer informações relativas ao mesmo;
 * Retirar ou alterar, total ou parcialmente, os avisos de reserva de direito existente no SOFTWARE e na documentação;
 * Praticar de engenharia reversa, descompilação ou desmontagem do SOFTWARE.
 * Estando totalmente sujeito a suspensão imediata da utilização do software e cancelamento do período de contratação, 
 * sem quaisquer restituições contratuais por parte da LICENCIANTE.  
 *
 */
use Symfony\Component\Cache\Adapter\AdapterInterface;

class UserCacheService 
{
    // In seconds
    const EXPIRATION = 600;
    
    private $cache;
    
    private $expiration;
    
    public function __construct(AdapterInterface $CacheAdapter, int $expiration = self::EXPIRATION) 
    {
        $this->cache = $CacheAdapter;
        $this->expiration = $expiration;
    }
    
    public function saveAllUsers(array $users, int $expire = self::EXPIRATION)  : void 
    {
        $allUsers = $this->cache->getItem('list.users');
        $allUsers->expiresAfter($expire);
        $allUsers->set($users);
        $this->cache->save($allUsers);
    }
    
    public function getAllUsers() : ?array 
    {
        $allUsers = $this->cache->getItem('list.users');
        if ($allUsers->isHit()) {
            return $allUsers->get();
        } else {
            return null;
        }       
    }
    
    public function increaseUsersCount(int $value) : void 
    {
        $totalUsers = $this->cache->getItem('stats.users_count');
        if ($totalUsers->isHit()) {
            $t = $totalUsers->get();
            $totalUsers->set($t+$value);
            $this->cache->save($totalUsers);
        }
    }
    
    public function decreaseUsersCount(int $value) : void 
    {
        $totalUsers = $this->cache->getItem('stats.users_count');
        if ($totalUsers->isHit()) {
            $t = $totalUsers->get();
            $totalUsers->set($t-$value);
            $this->cache->save($totalUsers);
        }
    }
    
    public function setTotalUsers(int $value) : void 
    {
        $totalUsers = $this->cache->getItem('stats.users_count');
        $totalUsers->set($value);
        $this->cache->save($totalUsers);
        return;
    }
    
    public function getTotalUsers() : ?int 
    {
        $totalUsers = $this->cache->getItem('stats.users_count');
        if ($totalUsers->isHit()) {
            return $totalUsers->get();
        } else {
            return null;
        }
    }
    
    public function hasItem(string $item) : bool 
    {
        $item = $this->cache->getItem($item);
        return ($item->isHit()) ? true : false;
    }
    
}