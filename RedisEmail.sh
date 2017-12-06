#!/bin/sh
#定义目录路径
prefix='/root/shell'

#延时秒数
second=4

#进程名称
pid_name="${prefix}/RedisEmail.pid"

#日志文件
log_name="${prefix}/RedisEmail.log"

#要运行的文件文件名
run_name="${prefix}/RedisEmail.php"

#使用nohup忽略捕获到的退出信号
#运行指定的php文件，将错误(2:stderr)和标准输出(1:stdout)合并后追加到日志文件
#使用$将前面的命令放入后台运行
#通过$!获取后台运行的最后一个进程id,并重定向到新的进程里
nohup php ${run_name} >> ${log_name} 2>&1 & echo $! > ${pid_name}

#循环检测进程是否存在
while [ 1 ]; do
    if [ ! -d /proc/`cat ${pid_name}` ]; then
	nohup php ${run_name} >> ${log_name} 2>&1 & echo $! > ${pid_name};
	echo 'new_pid:'`cat ${pid_name} && date '+%Y-%m-%d %H:%M:%S'`
    fi
    sleep ${second}
done

#启动方式：nohup  xxx.sh >> xxx.sh.log  2>&1 &
