<?php

namespace models;

use PDO; 

class CompanyModel extends BaseModel
{
	function __construct() {}

    public function getCompanyDetails() {
        try {
            $db = parent::connectToDB();
            $query = $db->prepare('
            
            SELECT
            c.*, p.city
            FROM company_details c
            INNER JOIN postal_codes p ON c.postal_code_id=p.postal_code_id
            ');
            $query->execute();

            return $query->fetch(PDO::FETCH_OBJ);

      
        } catch (\PDOException $ex) {
            print($ex->getMessage());
        } finally {
            parent::closeConnection();
        }
    }


    public function updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $phoneNumber, $email)
    {
    try {
        $db = parent::connectToDB();
        $query = $db->prepare('
            UPDATE company_details
            SET company_name = :company_name, street = :street, postal_code_id = :postal_code_id, email = :email, opening_hours = :opening_hours, phone_number = :phone_number
            WHERE company_details_id = :company_details_id
        ');

        $query->bindParam(':company_details_id', $companyId);
        $query->bindParam(':company_name', $companyName);
        $query->bindParam(':street', $street);
        $query->bindParam(':postal_code_id', $postalCodeId);
        $query->bindParam(':phone_number', $phoneNumber);
        $query->bindParam(':email', $email);
        $query->bindParam(':opening_hours', $openingHours);


        $query->execute();
    } catch (\PDOException $ex) {
        // Handle errors (log or rethrow the exception)
        throw $ex;
    } finally {
        parent::closeConnection();
    }
}




}
