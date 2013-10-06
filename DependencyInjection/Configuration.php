<?php

namespace Rodgermd\ContestNominationsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  /**
   * Generates the configuration tree.
   *
   * @return TreeBuilder
   */
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();
    $rootNode    = $treeBuilder->root('rodgermd_contest_nominations');

    return $treeBuilder;
  }
}
