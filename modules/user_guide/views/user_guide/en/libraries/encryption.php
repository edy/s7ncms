Article status [First draft] requires [Editing] Describe class
# Encryption Class
Todo Describe the class

## Methods

### Encode
<code>$this->encrypt->encode()</code> accepts multiple parameters. The input **string** is required.
Returns an encoded string.

    $encoded_message = $this->encrypt->encode($str, $key = '');

****

### Decode
<code>$this->encrypt->decode()</code> accepts multiple parameters. The input **string** is required.
Returns a decoded string.

    $decoded_message = $this->encrypt->decode($str, $key = '');


****

### Hash
<code>$this->encrypt->hash()</code> accepts one mandatory parameter. The input **string** is required.
Returns a message digest.

    $sha_digest = $this->encrypt->hash($str);



<?php echo $this->load->view('user_guide/en/abbr') ?>
<?php /* $Id: encryption.php 644 2007-10-04 14:45:05Z Shadowhand $ */ ?>