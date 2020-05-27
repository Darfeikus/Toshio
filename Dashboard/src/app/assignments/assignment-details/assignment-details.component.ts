import { Component, OnInit } from "@angular/core";

@Component({
  selector: "app-assignment-details",
  templateUrl: "./assignment-details.component.html",
  styleUrls: ["./assignment-details.component.css"],
})
export class AssignmentDetailsComponent implements OnInit {
  /*
  ! Debe de recibir como input el fileList y cambiar el contenido de la funcion onChange dentro de ngOnInit
  * Ver la posibilidad que esto sea un modal en caso que no se pueda
  ? o pasar ID the file via url y hacer request async
  */
  fileContent: string | ArrayBuffer;

  public onChange(fileList: FileList): void {
    console.log("filelist", fileList);
    let file = fileList[0];
    console.log("file", file);
    let fileReader: FileReader = new FileReader();
    let self = this;
    fileReader.onloadend = function (x) {
      self.fileContent = fileReader.result;
    };
    fileReader.readAsText(file);
  }

  constructor() {}

  ngOnInit(): void {}
}
