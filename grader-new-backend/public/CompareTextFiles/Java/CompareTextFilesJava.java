import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.FileReader;
import java.io.IOException;
import java.util.Scanner;

public class CompareTextFilesJava {
	public static void main(String[] args) throws IOException {
		Scanner sc = new Scanner(System.in);

		String input[] = sc.nextLine().split("#");

		String matricula = input[0];
		String id = input[1];
		int numberTestCases = Integer.parseInt(input[2]);

		double grade = 0;

		String comments = "\n";

		for (int i = 1;i<=numberTestCases ;i++ ) {

			BufferedWriter bw = null;
			FileWriter fw = null;

			BufferedReader reader = new BufferedReader(new FileReader("testCases/"+id+"/retro" + i));

			BufferedReader reader1 = new BufferedReader(new FileReader("uploads/"+matricula+"/"+id+"/out" + i ));

			BufferedReader reader2 = new BufferedReader(new FileReader("testCases/"+id+"/out"+ i));

			String line = reader.readLine();

			String line1 = reader1.readLine();

			String line2 = reader2.readLine();

			boolean areEqual = true;


			while (line1 != null || line2 != null)
			{
				if(line1 == null || line2 == null)
				{
					areEqual = false;

					break;
				}
				else if(!line1.equalsIgnoreCase(line2))
				{
					areEqual = false;

					break;
				}

				line1 = reader1.readLine();

				line2 = reader2.readLine();

			}

			if(areEqual)
				grade += 100/numberTestCases;
			else
				comments += line + "\n";
			if(i==numberTestCases){
				try {
					String content = ""+grade;
					fw = new FileWriter("uploads/"+matricula+"/"+id+"/ResultsOutJava");
					bw = new BufferedWriter(fw);
					bw.write(content);
					bw.write(comments);
				} catch (IOException e) {
					e.printStackTrace();
				}
				finally {
					try {
						if (bw != null)
							bw.close();
						if (fw != null)
							fw.close();
					} catch (IOException ex) {

						ex.printStackTrace();
					}
				}
				reader.close();

				reader1.close();

				reader2.close();
			}
		}
	}
}
