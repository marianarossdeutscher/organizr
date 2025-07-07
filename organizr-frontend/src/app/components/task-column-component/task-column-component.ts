import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TaskCardComponent } from '../task-card-component/task-card-component';
import { Task } from '../../models/task-model.model';

@Component({
  selector: 'app-task-column',
  standalone: true,
  imports: [CommonModule, TaskCardComponent],
  templateUrl: './task-column-component.html',
  styleUrls: ['./task-column-component.scss']
})
export class TaskColumnComponent {
  @Input() title: string = '';
  @Input() tasks: Task[] = [];

  @Output() addTask = new EventEmitter<string>();
  @Output() editTask = new EventEmitter<Task>();
  @Output() deleteTask = new EventEmitter<number>();

  onEditTask(task: Task): void {
    this.editTask.emit(task);
  }

  onDeleteTask(taskId: number): void {
    this.deleteTask.emit(taskId);
  }
}