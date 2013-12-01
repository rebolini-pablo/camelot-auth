<?php namespace T4s\CamelotAuth\Auth\Saml2\Messages;

use T4s\CamelotAuth\Auth\Saml2\Messages\AbstractMessage;
use T4s\CamelotAuth\Auth\Saml2\Metadata\EntityMetadata;
use T4s\CamelotAuth\Auth\Saml2\Saml2Constants;

use T4s\CamelotAuth\Auth\Saml2\Assertion;

class ResponseMessage extends AbstractMessage
{
	/**
	 * A list of all the assertions in this response
	 *
	 * @var array
	 */
	protected $assertions = array();


	public function __construct($message = null,EntityMetadata $spMetadata = null)
	{
		parent::__construct('Response',$message);
		
		if(is_null($message))
		{
			return;
		}

		if($message instanceof EntityMetadata)
		{
			$this->importMetadataSettings($message,$spMetadata);
		}
	}
	
	public function importXMLMessage(\DOMElement $message)
	{
		parent::importXMLMessage($message);
		
		foreach ($message->childNodes as $node) {
			
			if($node->namespaceURI != Saml2Constants::Namespace_SAML)
			{
				continue;
			}

			if($node->localName == "Assertion")
			{
				$this->assertions[] = new Assertion($node);
			}
			else if($node->localName == "EncryptedAssertion")
			{
				$this->assertions[] = new EncryptedAssertion($node);
			}
		}
	}

	public function getAssertions()
	{
		return $this->assertions;
	}
}