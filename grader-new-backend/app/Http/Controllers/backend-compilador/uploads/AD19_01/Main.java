import java.util.Scanner;

public class Main{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        String str = sc.nextLine();
        int j = str.length();
        while(j>0 && isPalindrome(str.substring(0,j)))
            j--;
        System.out.println(j);
    }
    
    public static boolean isPalindrome(String str){
        for(int i = 0;i<str.length();i++)
            if(str.charAt(i)!=str.charAt(str.length()-i-1))
                return false;
        return true;
    }

}
