<?php
	function checkRole($aut_role)
	{
		if(isset($_SESSION['niveau']) && in_array($_SESSION['niveau'], $aut_role))
		{
			return true;
		}else
		{
			return false;
		}
	}
	 
	function generate_csrf()
	{
		$token = bin2hex(random_bytes(32));
		$_SESSION['token_csrf'] = $token;
		return $token;
	}

	function inputtest($data)
	{
		$data=trim($data);
		$data=stripcslashes($data);
		$data=htmlspecialchars($data,ENT_QUOTES,'UTF-8');
		return $data;
	}
	//VERIFICATION 
	function checkDataUser($data)
	{
		$conn = Connexion::connect();
		$sql = "SELECT email_utl FROM utilisateurs WHERE email_utl = :email AND control_deleted = 'no_deleted' AND is_actif = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':email',$data);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}

	function checkDataInviter($data)
	{
		$conn = Connexion::connect();
		$sql = "SELECT email_inviter FROM comite_organisateur WHERE email_inviter = :email AND control_deleted = 'no_deleted' AND is_actif_inviter = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':email',$data);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}

	function  checkAllData($table,$col,$data)
	{
		$conn = Connexion::connect();
		$sql = "SELECT $col FROM $table WHERE $col = :col ";
		$query = $conn->prepare($sql);
		$query->bindParam(':col',$data);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}

	function  checkCategorie($n,$id1,$id2)
	{
		$conn = Connexion::connect();
		$sql = "SELECT nom_categorie,id_event_ctg ,id_org_ctg_fk FROM categorie_inviter WHERE  nom_categorie = :n AND id_event_ctg = :id1 AND id_org_ctg_fk = :id2 AND date_enreg_cat = 'no_deleted'";
		$query = $conn->prepare($sql);
		$query->bindParam(':n',$n,PDO::PARAM_STR);
		$query->bindParam(':id1',$id1,PDO::PARAM_INT);
		$query->bindParam(':id2',$id2,PDO::PARAM_INT);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}

	function checkAffectQrRfid($rfid,$qr)
	{
		$conn = Connexion::connect();
		$sql = "SELECT code_invitation,code_qr_invitation FROM invitations WHERE code_invitation = :col AND code_qr_invitation = :col1";
		$query = $conn->prepare($sql);
		$query->bindParam(':col',$rfid);
		$query->bindParam(':col1',$qr);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}

	function checkEvent($id_u)
	{
		$conn = Connexion::connect();
		$sql = "SELECT id_util_org FROM evenements WHERE id_util_org = :id_u AND control_deleted = 'no_deleted' AND is_actif = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id_u',$id_u,PDO::PARAM_INT);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}
	function checkAffectCode($invt,$event)
	{
		$conn = Connexion::connect();
		$sql = "SELECT affect_invit_id ,affect_event_id FROM affect_invitation_event WHERE affect_invit_id = :col AND affect_event_id = :col1 AND control_deleted = 'no_deleted' AND is_actif ='active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':col',$invt);
		$query->bindParam(':col1',$event);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}
	function checkAffectCodePart($invt,$verif)
	{
		$conn = Connexion::connect();
		if($verif == 'qr'){
		$sql = "SELECT code_qr_invitation FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env WHERE INV.code_qr_invitation = :id AND AF.control_deleted = 'no_deleted' AND AF.is_actif ='active'";
	}elseif($verif == 'rfid'){
		$sql = "SELECT code_invitation FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env WHERE INV.code_invitation = :id AND AF.control_deleted = 'no_deleted' AND AF.is_actif ='active'";
	}
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$invt);
		$query->execute();
		$row = $query->rowCount();
		return $row;

	}
	function checkAffectCodePartScan($Dresult,$id){
		$conn = Connexion::connect();
		$sql = "SELECT * FROM invitations iv LEFT JOIN affect_invitation_event af ON af.affect_invit_id = iv.id_inv 
            INNER JOIN participants p ON p.id_invitation_part_fk = af.id_aff
            INNER JOIN evenements e ON af.affect_event_id = e.id_env
            INNER JOIN categorie_inviter ct ON p.id_cat_inviter_fk = ct.id_cat_inv
            WHERE code_invitation = :Dresult AND af.affect_event_id = :id AND p.etat_participant='confirme' AND af.control_deleted = 'no_deleted'
            OR code_qr_invitation = :Dresult AND af.affect_event_id = :id AND p.etat_participant='confirme' AND af.control_deleted = 'no_deleted'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Dresult',$Dresult,PDO::PARAM_STR);
            $stmt->bindParam(':id',$id,PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->rowCount();
		return $result;
	}
	//checked invitation
	function checkAllDataInvitation($data)
	{
		$conn = Connexion::connect();
		$sql = "SELECT id_invitation_part_fk  FROM participants WHERE id_invitation_part_fk = :col AND control_deleted = 'no_deleted' AND is_actif_part = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':col',$data);
		$query->execute();
		$row = $query->rowCount();
		return $row;
	}
	//LISTE

	/* === =============================== ===
		LISTE COUNT
	=== ================================= ===*/
	function nbrInvitation($id,$conn){
		$sql = "SELECT DISTINCT(COUNT(id_aff)) as total FROM affect_invitation_event WHERE affect_event_id = :id AND control_deleted = 'no_deleted' AND is_actif = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$res=$query->fetch();
		return $res['total'];
	}
	function nbrParticipant($id,$conn){
		$sql = "SELECT DISTINCT(COUNT(id_part)) as total FROM participants WHERE id_event_part_fk = :id AND control_deleted = 'no_deleted' AND is_actif_part = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$res=$query->fetch();
		return $res['total'];
	}
	function nbrParticipantId($id,$conn){
		$sql = "SELECT DISTINCT(COUNT(id_part)) as total FROM participants WHERE id_cmt_osp_part_fk = :id AND control_deleted = 'no_deleted' AND is_actif_part = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$res=$query->fetch();
		return $res['total'];
	}
	function nbrParticipee($id,$conn){
		$sql = "SELECT DISTINCT(COUNT(id_part)) as total FROM participants WHERE id_event_part_fk = :id AND etat_participant = 'confirme' AND control_deleted = 'no_deleted' AND is_actif_part = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$res=$query->fetch();
		return $res['total'];
	}
	function nbrParticipeeId($id,$conn){
		$sql = "SELECT DISTINCT(COUNT(id_part)) as total FROM participants WHERE id_cmt_osp_part_fk = :id AND etat_participant = 'confirme' AND control_deleted = 'no_deleted' AND is_actif_part = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$res=$query->fetch();
		return $res['total'];
	}

	function nbrComitard($id,$conn){
		$sql = "SELECT DISTINCT(COUNT(id_inviter)) as total FROM comite_organisateur WHERE id_event_cm_fk = :id AND control_deleted = 'no_deleted' AND is_actif_inviter = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$res=$query->fetch();
		return $res['total'];
	}
	/* === =============================== ===
		LISTE FECT()
	=== ================================= ===*/
	//liste info pour un utilisateur

	function getUsers($id,$conn)
	{
 
		$sql = "SELECT * FROM utilisateurs u LEFT JOIN niveaux n ON u.id_niveau_fk = n.id_niv WHERE u.id_utl = :id AND u.control_deleted = 'no_deleted' AND u.is_actif = 'active'";
		$res = $conn->prepare($sql);
		$res->bindParam(':id',$id,PDO::PARAM_INT);
		$res->execute();
		$result = $res->fetch();
		if($result){
			return $result;
		}
	}
		//liste utilisateur
	function lsUsers()
	{
		 $conn = Connexion::connect();
		$sql = "SELECT * FROM utilisateurs WHERE control_deleted = 'no_deleted' AND is_actif = 'active'";
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}

		//liste niveau
	function lsniveau($data = null)
	{
		 $conn = Connexion::connect();
		 if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0'){
		 	$sql = "SELECT * FROM niveaux WHERE is_actif_niv = 'active'";
		 }else{

		 	$sql = "SELECT * FROM niveaux WHERE nom_niv='$data' AND is_actif_niv = 'active'";
		 }
		
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}

		//liste type d'événement
	function lsTypeEvenememt()
	{
		 $conn = Connexion::connect();
		$sql = "SELECT * FROM type_evenement";
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}

		//liste d'événement
	function lsEvenememt()
	{
		 $conn = Connexion::connect();
		$sql = "SELECT * FROM evenements WHERE control_deleted = 'no_deleted' AND is_actif = 'active'";
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}

		//liste type d'invitation
	function lsTypeInvitation()
	{
		 $conn = Connexion::connect();
		$sql = "SELECT * FROM types_invitation";
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}

	//liste d'invitation
	function lsInvitation()
	{
		 $conn = Connexion::connect();
		$sql = "SELECT * FROM invitations WHERE control_deleted_invit = 'no_deleted'";
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll(PDO::FETCH_ASSOC);
		if($result){
			return $result;
		}
	}

	function getInvitation($data,$verif)
	{
		$conn = Connexion::connect();
		if($verif == 'rfid'){
			$sql = "SELECT id_inv,code_invitation FROM invitations WHERE code_invitation = :code AND control_deleted_invit = 'no_deleted'";
		}elseif($verif == 'qr')
		{
			$sql = "SELECT id_inv,code_qr_invitation FROM invitations WHERE code_qr_invitation = :code AND control_deleted_invit = 'no_deleted'";
		}
		$res = $conn->prepare($sql);
		$res->bindParam(':code',$data,PDO::PARAM_STR);
		$res->execute();
		$result = $res->fetch();
		if($result){
			return $result;
		}
	}

	function getInvitation1($id,$conn)
	{
		$sql = "SELECT * FROM invitations WHERE id_inv  = :id AND control_deleted_invit = 'no_deleted'";
		$res = $conn->prepare($sql);
		$res->bindParam(':id',$data,PDO::PARAM_INT);
		$res->execute();
		$result = $res->fetch();
		return $result;
	}
	// liste catégorie Inviter
	function lsDefaultCategorieInviter($verif)
	{
		$conn = Connexion::connect();
		if($verif == 'fete'){
			$sql = "SELECT * FROM categorie_inviter WHERE 
			id_event_ctg = 0 AND controle_deleted_cat = 'no_deleted'
			OR sigle_cat_inviter = 'fm_girls' AND controle_deleted_cat = 'no_deleted' 
			OR sigle_cat_inviter = 'fm_man' AND controle_deleted_cat = 'no_deleted'";
		}else{
			$sql = "SELECT * FROM categorie_inviter WHERE 
			id_event_ctg = 0 AND controle_deleted_cat = 'no_deleted'
			OR sigle_cat_inviter != 'fm_girls' AND controle_deleted_cat = 'no_deleted' 
			OR sigle_cat_inviter != 'fm_man' AND controle_deleted_cat = 'no_deleted'";
		}
		
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}
	function lsCategorieInviter($data)
	{
		$conn = Connexion::connect();
		$sql = "SELECT * FROM categorie_inviter WHERE id_event_ctg = :id AND  controle_deleted_cat = 'no_deleted'";
		$res = $conn->prepare($sql);
		$res->bindParam(':id',$data,PDO::PARAM_INT);
		$res->execute();
		$result = $res->fetchAll();
		if($result){
			return $result;
		}
	}
	//Sélection du nom d'un événement
	function lsEventUser()
	{
		$conn = Connexion::connect();
		$sql = "SELECT t.nom_evenement FROM evenements e LEFT JOIN utilisateurs u ON e.id_util_org = u.id_utl 
		JOIN type_evenement t ON t.id_typ_even = e.id_type_envent_fk 
		JOIN comite_organisateur c ON u.id_utl = c.id_utl_inviter_fk  
		WHERE e.control_deleted = 'no_deleted'";
		$res = $conn->prepare($sql);
		$res->execute();
		$result = $res->fetch();
		if($result){
			return $result;
		}
	}

	// Sélection d'un utilisateur et son événement
	function lsEventOneline($data)
	{
		$conn = Connexion::connect();
		$sql = "SELECT * FROM evenements  
		WHERE id_util_org = :id AND control_deleted = 'no_deleted' AND is_actif='active'";
		$res = $conn->prepare($sql);
		$res->bindParam(':id',$data,PDO::PARAM_INT);
		$res->execute();
		$result = $res->fetch();
		if($result){
			return $result;
		}
	}

	function lsEventUsersId($id)
	{
		$conn = Connexion::connect();
		$sql = "SELECT * FROM evenements ev LEFT JOIN type_evenement ty ON ev.id_type_envent_fk = ty.id_typ_even 
		INNER JOIN utilisateurs u ON ev.id_util_org = u.id_utl
		WHERE ev.id_util_org = :id AND ev.control_deleted = 'no_deleted' AND ev.is_actif='active'";
		$res = $conn->prepare($sql);
		$res->bindParam(':id',$id,PDO::PARAM_INT);
		$res->execute();
		$result = $res->fetch();
		if($result){
			return $result;
		}
	}

	function getEventId($id){
		$conn = Connexion::connect();
		$sql = "SELECT * FROM evenements ev JOIN type_evenement ty ON ev.id_type_envent_fk = ty.id_typ_even WHERE ev.id_env = :id AND ev.control_deleted = 'no_deleted' AND is_actif = 'active'";
	    $query = $conn->prepare($sql);
	    $query->bindParam(':id',$id,PDO::PARAM_INT);
	    $query->execute();
	    $result = $query->fetch();
	    return $result;
	}
	//Infor sur dernier membre du comite
	function infolastComite($conn,$id){
		$sql = "SELECT * FROM comite_organisateur WHERE id_utl_inviter_fk = :id AND control_deleted ='no_deleted' AND is_actif_inviter = 'active' ORDER BY id_inviter DESC LIMIT 1";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $query->fetch();
	}
	function getComitard($id,$conn){
		$sql = "SELECT * FROM comite_organisateur c LEFT JOIN niveaux n ON c.id_niveau_inv_fk = n.id_niv WHERE id_inviter = :id AND control_deleted ='no_deleted' AND is_actif_inviter = 'active'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $query->fetch();
	}
	function getCategoriePlace($id,$conn){
		$sql = "SELECT * FROM categorie_inviter WHERE id_cat_inv = :id AND controle_deleted_cat ='no_deleted'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $query->fetch();
	}
	function lsAffect_invitation_event($id)
	{
		$conn = Connexion::connect();
		$sql = "SELECT * FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env WHERE EV.id_env = :id AND AF.control_deleted = 'no_deleted' AND AF.is_actif ='active' ORDER BY id_aff ASC";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	function lsAffectCodePart($invt,$id_org,$verif)
	{
		$conn = Connexion::connect();
		if($verif == 'qr'){
		$sql = "SELECT * FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env
		INNER JOIN utilisateurs
		WHERE INV.code_qr_invitation = :id AND EV.id_util_org = :id_org AND AF.control_deleted = 'no_deleted' AND AF.is_actif ='active'";

	}elseif ($verif == 'rfid') {
		$sql = "SELECT * FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env
		INNER JOIN utilisateurs
		WHERE INV.code_invitation = :id AND EV.id_util_org = :id_org AND AF.control_deleted = 'no_deleted' AND AF.is_actif ='active'";
	}
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$invt);
		$query->bindParam(':id_org',$id_org);
		$query->execute();
		$row = $query->fetch();
		return $row;

	}

	function UsersComiteOneline($data)
	{
		$conn = Connexion::connect();
		if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){

			$sql = "SELECT * FROM utilisateurs u LEFT JOIN evenements e ON u.id_utl = e.id_env WHERE u.id_utl = :id AND u.control_deleted = 'no_deleted' AND u.is_actif = 'active'";

		}else{
			$sql = "SELECT * FROM comite_organisateur c LEFT JOIN utilisateurs u ON c.id_utl_inviter_fk = u.id_utl 
					INNER JOIN evenements e ON c.id_event_cm_fk = e.id_env WHERE c.id_inviter = :id AND c.control_deleted = 'no_deleted' AND c.is_actif_inviter = 'active'";
		}
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$data);
		$query->execute();
		$row = $query->fetch();
		return $row;
	}
	function getPassword($data)
	{
		$conn = Connexion::connect();
		if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){

			$sql = "SELECT password_utl as password FROM utilisateurs WHERE id_utl = :id ";

		}else{
			$sql = "SELECT password_inviter as password FROM comite_organisateur WHERE id_inviter = :id";
		}
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$data);
		$query->execute();
		$row = $query->fetch();
		return $row;
	}

	//Liste de participants
	function lsParticipants($id)
	{
		$conn = Connexion::connect();
		if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){
		$sql = "SELECT * FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env
		INNER JOIN participants P ON P.id_invitation_part_fk = INV.id_inv  
		WHERE P.id_event_part_fk = :id AND P.control_deleted = 'no_deleted' AND P.is_actif_part ='active' ORDER BY date_enreg_part DESC";
	}else{
		$sql = "SELECT * FROM affect_invitation_event AF LEFT JOIN invitations INV ON AF.affect_invit_id = INV.id_inv 
		INNER JOIN evenements EV ON AF.affect_event_id = EV.id_env
		INNER JOIN participants P ON P.id_invitation_part_fk = INV.id_inv  
		WHERE P.id_cmt_osp_part_fk = :id AND P.control_deleted = 'no_deleted' AND P.is_actif_part ='active' ORDER BY date_enreg_part DESC";
	}
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	function getParticepant($id,$conn){
		$sql = "SELECT * FROM participants WHERE id_part = :id";
		$query = $conn->prepare($sql);
		$query->bindParam(':id', $id,PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch();
		return $result;
	}

	//PARTIE COMITE 
	function lsAllcomite($id)
	{
		$conn = Connexion::connect();
		$sql = "SELECT * FROM comite_organisateur c LEFT JOIN utilisateurs u ON c.id_utl_inviter_fk = u.id_utl
		INNER JOIN evenements e ON c.id_event_cm_fk = e.id_env
		INNER JOIN type_evenement t ON t.id_typ_even = e.id_type_envent_fk
		WHERE c.id_utl_inviter_fk = :id AND c.control_deleted = 'no_deleted'";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $result = $query->fetchAll(PDO::FETCH_ASSOC);
	}
	function lsAllcomiteLimit($id,$conn)
	{
		$sql = "SELECT * FROM comite_organisateur c LEFT JOIN utilisateurs u ON c.id_utl_inviter_fk = u.id_utl
		INNER JOIN evenements e ON c.id_event_cm_fk = e.id_env
		INNER JOIN type_evenement t ON t.id_typ_even = e.id_type_envent_fk
		WHERE c.id_event_cm_fk = :id AND c.control_deleted = 'no_deleted' ORDER BY id_inviter ASC LIMIT 3";
		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id,PDO::PARAM_INT);
		$query->execute();
		return $result = $query->fetchAll(PDO::FETCH_ASSOC);
	}
	//END PARTIE COMITE
	//END LISTE

	/* ALERT 
	====================*/
	
	function nbreDbInvitPartic($id_event,$conn){
		$sql = "SELECT nom_part,id_cmt_osp_part_fk, id_ops_update_part,date_enreg_part,id_invitation_part_fk,COUNT(id_invitation_part_fk) AS nbre FROM participants WHERE id_event_part_fk = :id GROUP BY id_invitation_part_fk HAVING COUNT(id_invitation_part_fk) > 1";

		$query = $conn->prepare($sql);
		$query->bindParam(':id',$id_event,PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetchAll();
		return $result;
	}

	function getNotifications($id_event,$conn){
		$req=("SELECT p.*, e.id_env FROM participants p
			INNER JOIN evenements e ON p.id_event_part_fk = e.id_env
			WHERE p.id_event_part_fk = :id AND p.id_invitation_part_fk IN (SELECT id_invitation_part_fk FROM participants WHERE id_event_part_fk = :id GROUP BY id_invitation_part_fk HAVING COUNT(*) > 1)");
            $query = $conn->prepare($req);
            $query->bindParam(':id',$id_event,PDO::PARAM_INT);
            $query->execute();
            return $result = $query->fetchAll(PDO::FETCH_ASSOC);
	}

	function getUsersAll($id_ops,$conn){
            $sql1 = "SELECT email_utl as ops FROM utilisateurs WHERE id_utl = :id";
            $query1 = $conn->prepare($sql1);
            $query1->bindParam(':id',$id_ops);
            $query1->execute();
            return $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	}

	function getComiteAll($id_ops,$conn){
            $sql1 = "SELECT email_inviter as ops FROM comite_organisateur WHERE id_inviter = :id";
            $query1 = $conn->prepare($sql1);
            $query1->bindParam(':id',$id_ops);
            $query1->execute();
            return $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	}
?>