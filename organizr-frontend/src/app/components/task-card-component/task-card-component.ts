import { Component, Input, Output, EventEmitter, HostListener, OnInit } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { Task } from '../../models/task-model.model';

@Component({
  selector: 'app-task-card',
  standalone: true,
  imports: [CommonModule, DatePipe],
  templateUrl: './task-card-component.html',
  styleUrls: ['./task-card-component.scss']
})
export class TaskCardComponent {
  @Input() task!: Task;
  @Output() edit = new EventEmitter<Task>(); 
  @Output() delete = new EventEmitter<number>(); 

  isMenuOpen = false;

  toggleMenu(event: MouseEvent): void {
    event.stopPropagation();
    this.isMenuOpen = !this.isMenuOpen;
  }

  @HostListener('document:click')
  closeMenu() {
    this.isMenuOpen = false;
  }

  onEdit(event: MouseEvent): void {
    event.stopPropagation();
    this.edit.emit(this.task); 
    this.isMenuOpen = false;
  }

  onDelete(event: MouseEvent): void {
    console.log("Deleting task");
    event.stopPropagation();
    this.delete.emit(this.task.id); 
    this.isMenuOpen = false;
  }
}