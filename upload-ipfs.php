require 'vendor/autoload.php';

$ipfs = new \Cloutier\PhpIpfsApi\IPFS("localhost", 8080, 5001); // Connect to IPFS

// Add a file to IPFS
$hash = $ipfs->add(file_get_contents('path_to_certificate.pdf'));

echo "IPFS Hash: " . $hash;



//add certificate

require 'vendor/autoload.php';

use Web3\Web3;

$web3 = new Web3('http://localhost:8545'); // Connect to Ethereum node

// TODO: Add logic to call the addCertificate function and store data on the blockchain
