import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.FileReader;
import java.io.IOException;
import java.util.Scanner;

public class CompareTextFilesJava
{
	public static void main(String[] args) throws IOException
	{
		Scanner sc = new Scanner(System.in);
		
		BufferedReader matr = new BufferedReader(new FileReader("./current"));

		String matricula = matr.readLine();

		BufferedReader reader = new BufferedReader(new FileReader("./tcNum"));

		int numTestC = Integer.parseInt(reader.readLine());

		double calif = 0;

		String comments = "\n";

		for (int i = 1;i<=numTestC ;i++ ) {

			BufferedWriter bw = null;
			FileWriter fw = null;

			reader = new BufferedReader(new FileReader("./TestCases/retro" + i));

			BufferedReader reader1 = new BufferedReader(new FileReader("out" + i ));

			BufferedReader reader2 = new BufferedReader(new FileReader("./TestCases/out" + i));

			String line = reader.readLine();

			String line1 = reader1.readLine();

			String line2 = reader2.readLine();

			if (i == 1){
				BufferedReader reader3 = new BufferedReader(new FileReader("tries"));
				String line3 = reader3.readLine();
				fw = new FileWriter("tries");
				bw = new BufferedWriter(fw);
				bw.write(Integer.toString(Integer.parseInt(line3)+1));
				bw.close();
				fw.close();
			}

			boolean areEqual = true;

			int lineNum = 1;

			while (line1 != null || line2 != null)
			{
				if(line1 == null || line2 == null)
				{
					areEqual = false;

					break;
				}
				else if(! line1.equalsIgnoreCase(line2))
				{
					areEqual = false;

					break;
				}

				line1 = reader1.readLine();

				line2 = reader2.readLine();

				lineNum++;
			}

			if(areEqual)
				calif += 100/numTestC;
			else
				comments += line + "\n";
			if(i==numTestC){
				try {
					String content = ""+calif;
					fw = new FileWriter("ResultsOutJava");
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
				reader1.close();

				reader2.close();
			}
		}
	}
}
