<?php

class DeclarantDocument
{
    protected $document;
    protected $etablissement = null;

    public function __construct(acCouchdbDocument $document)
    {
        $this->document = $document;
    }
    
    public function getIdentifiant()
    {
        return $this->document->identifiant;
    }

    public function getDeclarant()
    {
        return $this->document->declarant;
    }
    
   public function getDeclarantObject() {
       if(is_null($this->etablissement)) {
            if (sfConfig::get('app_declarant_class') == "Recoltant") {
                $this->etablissement = acCouchdbManager::getClient('Recoltant')->retrieveByCvi($this->getIdentifiant());                
            }else {
                $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($this->getIdentifiant());
            }
        }

        return $this->etablissement;
    }
    
    public function getEtablissementObject() {
        return $this->getDeclarantObject();
    }

    public function storeDeclarant()
    {
        $etablissement = $this->getEtablissementObject();
        if (!$etablissement) {

            throw new sfException(sprintf("L'etablissement %s n'existe pas", $this->getIdentifiant()));
        }
        $declarant = $this->getDeclarant();
        $declarant->nom = $etablissement->nom;
        $declarant->raison_sociale = $etablissement->getRaisonSociale();
        $declarant->cvi = $etablissement->cvi;
        $declarant->no_accises = $etablissement->getNoAccises();
        $declarant->adresse = $etablissement->siege->adresse;
        if ($etablissement->siege->exist("adresse_complementaire")) {
            $declarant->adresse .= ' ; '.$etablissement->siege->adresse_complementaire;
        }
        $declarant->commune = $etablissement->siege->commune;
        $declarant->code_postal = $etablissement->siege->code_postal;
        $declarant->region = $etablissement->getRegion();
        if ($etablissement->exist("telephone")) {
             $declarant->add('telephone',$declarant->telephone);
        }
        if ($etablissement->exist("email")) {
             $declarant->add('email',$declarant->email);
        }
         if ($etablissement->exist("fax")) {
            $declarant->add('fax',$declarant->fax);
        }
    }
}