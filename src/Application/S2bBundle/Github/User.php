<?php

namespace Application\S2bBundle\Github;
use Symfony\Components\Console\Output\OutputInterface;
use Application\S2bBundle\Document;

class User
{
    /**
     * php-github-api instance used to request GitHub API
     *
     * @var \phpGitHubApi
     */
    protected $github = null;

    /**
     * Output buffer
     *
     * @var OutputInterface
     */
    protected $output = null;
    
    public function __construct(\phpGitHubApi $github, OutputInterface $output)
    {
        $this->github = $github;
        $this->output = $output;
    }

    public function import($name)
    {
        $user = new Document\User();
        $user->setName($name);
        try {
            $this->update($user);
        }
        catch(\phpGitHubApiRequestException $e) {
            $this->output->writeLn(sprintf('%s is not a valid GitHub username', $name));
            $user = null;
        }
        return $user;
    }

    public function update(Document\User $user)
    {
        $data = $this->github->getUserApi()->show($user->getName());

        $user->setEmail(isset($data['email']) ? $data['email'] : null);
        $user->setFullName(isset($data['name']) ? $data['name'] : null);
        $user->setCompany(isset($data['company']) ? $data['company'] : null);
        $user->setLocation(isset($data['location']) ? $data['location'] : null);
        $user->setBlog(isset($data['blog']) ? $data['blog'] : null);
    }
    
    /**
     * Get output
     * @return OutputInterface
     */
    public function getOutput()
    {
      return $this->output;
    }
    
    /**
     * Set output
     * @param  OutputInterface
     * @return null
     */
    public function setOutput($output)
    {
      $this->output = $output;
    }
    
    /**
     * Get github
     * @return \phpGitHubApi
     */
    public function getGitHubApi()
    {
        return $this->github;
    }
    
    /**
     * Set github
     * @param  \phpGitHubApi
     * @return null
     */
    public function setGitHubApi($github)
    {
        $this->github = $github;
    }

}
