<?php
namespace OCA\Gestion\Controller;

use OCP\IRequest;
use OCP\Files\IRootFolder;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCA\Gestion\Db\Bdd;

class PageController extends Controller {
	private $idNextcloud;
	private $myDb;

	/** @var IRootStorage */
	private $storage;

	public function __construct($AppName, IRequest $request, $UserId, Bdd $myDb, IRootFolder $rootFolder){
		parent::__construct($AppName, $request);
		$this->idNextcloud = $UserId;
		$this->myDb = $myDb;
		try{
			$this->storage = $rootFolder->getUserFolder($this->idNextcloud);
		}catch(\OC\User\NoUserException $e){

		}
		

		\OCP\Util::addScript('gestion', 'bundle');
		\OCP\Util::addScript('gestion', '120.bundle');
		\OCP\Util::addScript('gestion', '513.bundle');
		\OCP\Util::addScript('gestion', '856.bundle');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function index() {
		return new TemplateResponse('gestion', 'index', array('path' => $this->idNextcloud));  // templates/index.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function devis() {
		return new TemplateResponse('gestion', 'devis', array('path' => $this->idNextcloud));  // templates/devis.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function facture() {
		return new TemplateResponse('gestion', 'facture', array('path' => $this->idNextcloud));  // templates/facture.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function produit() {
		return new TemplateResponse('gestion', 'produit', array('path' => $this->idNextcloud));  // templates/produit.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function config() {
		$this->myDb->checkConfig($this->idNextcloud);
		return new TemplateResponse('gestion', 'configuration', array('path' => $this->idNextcloud));  // templates/configuration.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function devisshow($numdevis) {
		$devis = $this->myDb->getOneDevis($numdevis,$this->idNextcloud);
		$produits = $this->myDb->getListProduit($numdevis, $this->idNextcloud);
		return new TemplateResponse('gestion', 'devisshow', array('configuration'=> $this->getConfiguration(), 'devis'=>json_decode($devis), 'produit'=>json_decode($produits), 'path' => $this->idNextcloud));
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function factureshow($numfacture) {
		$facture = $this->myDb->getOneFacture($numfacture,$this->idNextcloud);
		// $produits = $this->myDb->getListProduit($numdevis);
		return new TemplateResponse('gestion', 'factureshow', array('path' => $this->idNextcloud, 'configuration'=> $this->getConfiguration(), 'facture'=>json_decode($facture)));
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function getClients() {
		
		return $this->myDb->getClients($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function getConfiguration() {
		
		return $this->myDb->getConfiguration($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function getDevis() {
		
		return $this->myDb->getDevis($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function getFactures() {
		
		return $this->myDb->getFactures($this->idNextcloud);
	}
	
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
    */
	public function getProduits() {
		
		return $this->myDb->getProduits($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $numdevis
    */
	public function getProduitsById($numdevis) {
		return $this->myDb->getListProduit($numdevis, $this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $id
    */
	public function getClient($id) {
		
		return $this->myDb->getClient($id, $this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $id
    */
	public function getClientbyiddevis($id) {
		
		return $this->myDb->getClientbyiddevis($id, $this->idNextcloud);
	}
	
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function insertClient() {
		return $this->myDb->insertClient($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function insertDevis(){
		return $this->myDb->insertDevis($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function insertFacture(){
		return $this->myDb->insertFacture($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function insertProduit(){
		return $this->myDb->insertProduit($this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $id
	 */
	public function insertProduitDevis($id){
		return $this->myDb->insertProduitDevis($id, $this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $table
	 * @param string $column
	 * @param string $data
	 * @param string $id
	 */
	public function update($table, $column, $data, $id) {
		return $this->myDb->gestion_update($table, $column, $data, $id, $this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $table
	 * @param string $id
	 */
	public function delete($table, $id) {
		return $this->myDb->gestion_delete($table, $id, $this->idNextcloud);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $content
	 * @param string $folder
	 * @param string $name
	 */
	public function savePDF($content, $folder, $name){

		try {
			$this->storage->newFolder($folder);
        } catch(\OCP\Files\NotPermittedException $e) {
            
        }

		try {
			try {
				$ff = $folder . $name . ".pdf";
				$this->storage->newFile($ff);
				$file = $this->storage->get($ff);
				$data = base64_decode($content);
				$file->putContent($data);
          	} catch(\OCP\Files\NotFoundException $e) {
               	
            }

        } catch(\OCP\Files\NotPermittedException $e) {

            throw new StorageException('Cant write to file');
        }

		//work
		// try {
        //     try {
        //         $file = $this->storage->get('/test/myfile2.txt');
        //     } catch(\OCP\Files\NotFoundException $e) {
        //         
        //        	$file = $this->storage->get('/myfile.txt');
        //     }

        //     // the id can be accessed by $file->getId();
        //     $file->putContent('myfile2');

        // } catch(\OCP\Files\NotPermittedException $e) {
        //     // you have to create this exception by yourself ;)
        //     throw new StorageException('Cant write to file');
        // }

		// //
		// $userFolder->touch('/test/myfile2345.txt');
		// $file = $userFolder->get('/test/myfile2345.txt');
		// $file->putContent('test');
		// //$file = $userFolder->get('myfile2.txt');
	}


	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getStats(){
		
		$res = array();
		$res['client'] = json_decode($this->myDb->numberClient())[0]->c;
		$res['devis'] = json_decode($this->myDb->numberDevis())[0]->c;
		$res['facture'] = json_decode($this->myDb->numberFacture())[0]->c;
		$res['produit'] = json_decode($this->myDb->numberProduit())[0]->c;

		return json_encode($res);
	}

}
