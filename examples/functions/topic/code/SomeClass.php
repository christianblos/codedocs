<?php

class SomeClass
{
    /**
     * @CodeDocs\Topic(id="myTopic")
     *
     * This text belongs to the topic.
     * It ends until the next annotation starts.
     *
     * @CodeDocs\Topic(id="anotherTopic")
     *
     * This is another one...
     */
    public function __construct()
    {
    }
}
