<?php
namespace REST;
use REST;
use Tonic;
/**
 * Base class for rest collection query handlers
 */
abstract class BaseQueryHandler extends Tonic\Resource {
	use Shifter;
	/**
	 * precheck function can reject request if needed
	 * @param $error Tonic\Response
	 */
	public static function precheck($params, $error) {
		return true;
	}

	/**
	 * DAO must use CollectionTrait!
	 */
	abstract static function getDAO();
	/**
	 * Post-add function (after PUT)
	 */
	public function postPut() { }

	/**
	 * @method POST
	 * @provides application/json
	 */
	function post() {
		return $this->put();
	}

	/**
	 * @method PUT
	 * @provides application/json
	 */
	function put() {
		$uid = $this->id;
		if ($uid != \UserSingleton::getInstance()->getId())
			return new Tonic\Response(Tonic\Response::FORBIDDEN);
		$cdao = static::getDAO();
		$params = (array) $this->request->data;

		$error = [];
		if (!static::precheck($params, $error))
			return $error;
		// we need to go deeper
		list($uid, $cid) = $this->getShifted();
		$cid = $cdao->addMod($uid, $params);
		if (is_array($cid) && isset($cid['error'])) {
			$r = ['error' => 'use POST to modify existing values'];
			return tonicResponse(Tonic\Response::BADREQUEST, $r);
		}
		$r = $cdao->get($uid, $cid);
		return tonicResponse(Tonic\Response::OK, $r);
	}

	/**
	 * @method GET
	 * @provides application/json
	 */
	function get() {
		list($uid, $cid) = $this->getShifted();
		$dao = static::getDAO();
		$r = $dao->get($uid);
		return tonicResponse(Tonic\Response::OK, $r);
	}
}
?>
