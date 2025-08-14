<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DockerRunMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docker:run-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MySQL Docker 컨테이너 실행';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $runCmd = ['docker', 'run'];

        // 컨테이너 이름
        $runCmd[] = '--name';
        $runCmd[] = 'fastview-pre-assignment-mysql';

        // MYSQL_ROOT_PASSWORD
        $runCmd[] = '-e';
        $runCmd[] = 'MYSQL_ROOT_PASSWORD=25ZwLrK2SFw9';

        // MYSQL_DATABASE
        $runCmd[] = '-e';
        $runCmd[] = 'MYSQL_DATABASE=fastview_pre_employment_assignment';

        // port
        $runCmd[] = '-p';
        $runCmd[] = '3306:3306';

        // 백그라운드 실행
        $runCmd[] = '-d';

        // 컨테이너 종료 시 자동 제거
        $runCmd[] = '--rm';

        // image
        $runCmd[] = 'mysql:8.0';

        $this->info('컨테이너 실행: '.implode(' ', $runCmd));

        $process = new Process($runCmd);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if (!$process->isSuccessful()) {
            $this->error('컨테이너 실행 실패');
            return self::FAILURE;
        }

        $this->info('컨테이너 실행 성공');
        return self::SUCCESS;
    }
}
