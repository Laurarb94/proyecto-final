import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/user.service';
import { CommonModule } from '@angular/common';
import { Observable, tap } from 'rxjs';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-user-list',
  imports: [CommonModule],
  templateUrl: './user-list.component.html',
  styleUrl: './user-list.component.css'
})
export class UserListComponent implements OnInit {

  users$!: Observable<any[]>; //  $ al final indica que es un Observable. Además pones ! para asegurar que esta variable se va a inciializar en ngOnit

  constructor(private userService: UserService) {}

  ngOnInit(): void {
    this.users$ = this.userService.getUsers(); // Llamas al servicio para recuperar la url
  }
  





} //cierre clase userlistcomponent
