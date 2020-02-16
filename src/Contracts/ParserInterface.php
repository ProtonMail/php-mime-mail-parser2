<?php

namespace PhpMimeMailParser\Contracts;

use PhpMimeMailParser\Exception;
use PhpMimeMailParser\Attachment;

interface ParserInterface
{
    /**
     * Attachment filename argument option for ->saveAttachments().
     */
    const ATTACHMENT_DUPLICATE_THROW  = 'DuplicateThrow';
    const ATTACHMENT_DUPLICATE_SUFFIX = 'DuplicateSuffix';
    const ATTACHMENT_RANDOM_FILENAME  = 'RandomFilename';

    public function setPath(string $path): ParserInterface;

    /**
     * Set the Stream resource we use to get the email text
     *
     * @param resource $stream
     * @throws Exception
     */
    public function setStream($stream): ParserInterface;

    /**
     * Set the email text
     *
     * @param string $data
     */
    public function setText($data): ParserInterface;

    /**
     * Retrieve a specific Email Header, without charset conversion.
     *
     * @param string $name Header name (case-insensitive)
     *
     * @return string[]|null
     * @throws Exception
     */
    public function getRawHeader($name): ?array;

    /**
     * Retrieve a specific Email Header
     *
     * @param string $name Header name (case-insensitive)
     *
     * @return string|array|bool
     */
    public function getHeader($name);

    /**
     * Retrieve all mail headers
     *
     * @return array
     * @throws Exception
     */
    public function getHeaders(): array;

    /**
     * Retrieve the raw mail headers as a string
     *
     * @return string
     * @throws Exception
     */
    public function getHeadersRaw();

    /**
     * Returns the email message body in the specified format
     *
     * @param string $type text, html or htmlEmbedded
     *
     * @return string Body
     * @throws Exception
     */
    public function getMessageBody($type = 'text');

    /**
     * Return an array with the following keys display, address, is_group
     *
     * @param string $name Header name (case-insensitive)
     *
     * @return array
     */
    public function getAddresses($name);

    /**
     * Returns the attachments contents in order of appearance
     *
     * @return Attachment[]
     */
    public function getInlineParts(string $type = 'text'): array;

    /**
     * Returns the attachments contents in order of appearance
     *
     * @return Attachment[]
     */
    public function getAttachments($include_inline = true);

    /**
     * Save attachments in a folder
     *
     * @param string $attach_dir directory
     * @param bool $include_inline
     * @param string $filenameStrategy How to generate attachment filenames
     *
     * @return array Saved attachments paths
     * @throws Exception
     */
    public function saveAttachments(
        $attach_dir,
        $include_inline = true,
        $filenameStrategy = self::ATTACHMENT_DUPLICATE_SUFFIX
    );

    /**
     * Retrieve the resource
     *
     * @return resource resource
     */
    public function getResource();

    /**
     * Retrieve the file pointer to email
     *
     * @return resource stream
     */
    public function getStream();

    /**
     * Retrieve the text of an email
     *
     * @return string|null data
     */
    public function getData();

    /**
     * Retrieve the parts of an email
     *
     * @return array parts
     */
    public function getParts();

    /**
     * Retrieve the charset manager object
     *
     * @return CharsetManager charset
     */
    public function getCharset(): CharsetManager;

    /**
     * Add a middleware to the parser MiddlewareStack
     * Each middleware is invoked when:
     *   a MimePart is retrieved by mailparse_msg_get_part_data() during $this->parse()
     * The middleware will receive MimePart $part and the next MiddlewareStack $next
     *
     * Eg:
     *
     * $Parser->addMiddleware(function(MimePart $part, MiddlewareStack $next) {
     *      // do something with the $part
     *      return $next($part);
     * });
     *
     * @param callable $middleware Plain Function or Middleware Instance to execute
     * @return void
     */
    public function addMiddleware(callable $middleware): void;
}
