<?php

namespace Octoprogress\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Octoprogress\Model\ProjectQuery;

use Silex\Application;

class ProjectSelectorType extends AbstractType
{
    protected $app;

    /**
     * {@inheritdoc}
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->app['session']->get('user');

        $projects = ProjectQuery::create()
            ->filterByUserId($user->getId())
            ->orderByName()
            ->find()
        ;

        $formOptions = array();
        foreach ($projects as $project)
        {
            if (empty($formOptions[$project->getGithubUserName()]))
            {
                $formOptions[$project->getGithubUserName()] = array(
                    'choices' => array(),
                    'actives' => array(),
                );
            }

            $formOptions[$project->getGithubUserName()]['name'] = $project->getGithubUserName();
            $formOptions[$project->getGithubUserName()]['choices'][$project->getId()] = sprintf("%s/%s", $project->getGithubUserName(), $project->getName());

            if ($project->getActive())
            {
                $formOptions[$project->getGithubUserName()]['actives'][] = $project->getId();
            }
        }

        if (isset($formOptions[$user->getLogin()]))
        {
            array_unshift($formOptions, $formOptions[$user->getLogin()]);
            unset($formOptions[$user->getLogin()]);
        }

        foreach ($formOptions as $options)
        {
            $builder->add(sprintf("%s_projects_list", $options['name']), 'choice', array(
                'choices' => $options['choices'],
                'data' => $options['actives'],
                'expanded' => true,
                'multiple' => true,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ProjectSelector';
    }
}
