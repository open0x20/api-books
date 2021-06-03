pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building...'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
                sh 'ls -al'
                withEnv(['PATH+EXTRA=/var/www/deployments/api-books/vendor/bin']) {
                    sshPublisher(
                        publishers: [
                            sshPublisherDesc(
                                configName: 'DEPLOY@WEB_HOST_0',
                                transfers: [
                                    sshTransfer(
                                        cleanRemote: true,
                                        excludes: '',
                                        execCommand: 'cd /var/www/deployments/api-books && /usr/sbin/composer install --ignore-platform-reqs && /usr/bin/php /var/www/deployments/api-books/bin/console cache:warmup',
                                        execTimeout: 120000,
                                        flatten: false,
                                        makeEmptyDirs: true,
                                        noDefaultExcludes: false,
                                        patternSeparator: '[, ]+',
                                        remoteDirectory: 'api-books',
                                        remoteDirectorySDF: false,
                                        removePrefix: '',
                                        sourceFiles: '**'
                                    )
                                ],
                                usePromotionTimestamp: false,
                                useWorkspaceInPromotion: false,
                                verbose: true
                            )
                        ]
                    )
                }
            }
        }
    }
}
