<?php
/**
 * This file is part of the Slack API Bundle, a Symfony bundle for Slack.com
 * Copyright (C) 2015  Tyler Romeo <tylerromeo@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace CastlePointAnime\SlackApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        /** @noinspection PhpUndefinedMethodInspection */
        $treeBuilder->root( 'slack_api' )
            ->fixXmlConfig( 'token' )
            ->performNoDeepMerging()
                ->children()
                    ->scalarNode( 'name' )
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->info( 'The readable name of the team, not the team ID' )
                    ->end()
                    ->arrayNode( 'tokens' )
                        ->useAttributeAsKey( 'type' )
                        ->prototype( 'array' )
                            ->beforeNormalization()
                                ->ifString()
                                ->then(
                                    function ( $v ) {
                                        return [ 'value' => $v ];
                                    }
                                )
                            ->end()
                            ->children()
                                ->scalarNode( 'value' )
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
