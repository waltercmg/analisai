# encoding=utf8  
# coding=UTF-8

import zipfile, os, datetime, json
from os import listdir
from datetime import date

import Constantes, Util

dados = dict()
todosLabels = []
imprimirLog = False 
lotacoes = []

def main():
    global dados
    p("Início do processamento...")
    if getArquivoZip():
        getArquivosCsv()
        
    p("Fim do processamento...")
    #imprimeDados()
    gerarRelatorios()
    print json.dumps(lotacoes)

def descompactar(arquivo):
    zip_ref = zipfile.ZipFile(Constantes.pastaArquivoZip+arquivo, 'r')
    zip_ref.extractall(Constantes.pastaArquivoCsv)
    zip_ref.close()

def getArquivoZip():
    retorno = False
    for f in listdir(Constantes.pastaArquivoZip):
        if f[-3:].lower() == "zip":
            p("Descompactando arquivo: " + f)
            descompactar(f)
            moverArquivo(f, Constantes.pastaArquivoZip, Constantes.pastaArquivoZipProc)
            retorno = True 
            break           
    return retorno

def getArquivosCsv():
    p("Buscando arquivos CSV")    
    for f in listdir(Constantes.pastaArquivoCsv):
        if f[-3:].lower() == "csv":
            p("Tratando arquivo: " + f)
            tratarCsv(f)
            moverArquivo(f, Constantes.pastaArquivoCsv, Constantes.pastaArquivoCsvProc)

def tratarCsv(arquivo):
    global dados
    global todosLabels
    f = open(Constantes.pastaArquivoCsv+arquivo, 'r')
    labels = quebrarLinha(f.readline())
    for linha in f:                      
        addDados(arquivo.split(Constantes.sepNmArq)[0], labels, linha)
    f.close()

def addDados(indicador, labels, linha):
    global dados
    array = quebrarLinha(linha)
    if not dados.get(array[0]):
        dados.update({array[0]:{}})
    if not dados.get(array[0]).get(indicador):
        dados.get(array[0]).update({indicador:{}})    
    if not dados.get(array[0]).get(indicador).get(array[1]):
        dados.get(array[0]).get(indicador).update({array[1]:{}})
    cont = 2
    for coluna in array[2:]:
        dados.get(array[0]).get(indicador).get(array[1]).update({labels[cont]:coluna})
        cont += 1

def addDadosI(indicador, labels, linha):
    global dados
    array = quebrarLinha(linha)
    if not dados.get(indicador):
        dados.update({indicador:{}})
    if not dados.get(indicador).get(array[0]):
        dados.get(indicador).update({array[0]:{}})    
    if not dados.get(indicador).get(array[0]).get(array[1]):
        dados.get(indicador).get(array[0]).update({array[1]:{}})
    cont = 2
    for coluna in array[2:]:
        p("Indicador: " + indicador)
        p(" >>Resp: " + array[0])
        p("  >>Mes: " + array[1])
        p("   >>Label: " + labels[cont] + " - Valor: " + coluna)        
        dados.get(indicador).get(array[0]).get(array[1]).update({labels[cont]:coluna})
        cont += 1

def apagarRelatorios():
    filelist = [ f for f in os.listdir(Constantes.pastaArquivosHTML)]
    for f in filelist:
        os.remove(os.path.join(Constantes.pastaArquivosHTML, f))
       
def gerarRelatorios():
    apagarRelatorios()
    f = open(Constantes.templateBasico, 'r')
    htmlBasico = ""
    for linha in f:
        htmlBasico += linha
    f.close()
    for lotacao, info in dados.iteritems():
        gerarArquivo(lotacao, htmlBasico)

def gerarArquivo(lotacao, htmlBasico):    
    for indicador, meses in dados.get(Constantes.txDepto).iteritems():
        if "<<"+indicador+">>" in htmlBasico:
            htmlBasico = htmlBasico.replace("<<"+indicador+">>",getHtmlIndicador(lotacao, indicador))

    f = open(Constantes.pastaArquivosHTML+lotacao.replace("/","_")+".html", 'w')
    f.write(htmlBasico.replace(Constantes.tagLotacao,lotacao[-5:]))
    f.close()

def getHtmlIndicador(lotacao, indicador):
    global lotacoes
    if lotacao not in lotacoes:
        lotacoes.append(lotacao)
    p("Lotação: " + lotacao + " - Indicador: " + indicador) 
    htmlLinhas = ""
    for mes, infos in sorted(dados.get(Constantes.txDepto).get(indicador).iteritems()):
        htmlMes = "<td>"+mes+"</td>"
        htmlLabels = ""
        htmlDepto = ""
        htmlLotacao = ""
        quantColunas = 0
        for info, valor in infos.iteritems():
            htmlLabels += "<td><i>"+info+"</i></td>"           
            htmlDepto += "<td>"+valor+"</td>"
            try:
                valorLotacao = dados.get(lotacao).get(indicador).get(mes).get(info)
            except AttributeError:
                valorLotacao = "N/A"
            htmlLotacao += "<td>"+valorLotacao+"</td>"
            quantColunas += 1
        htmlLinhas += "<tr>" + htmlMes + htmlLotacao + htmlDepto + "</tr>"

    htmlTh = "<tr><th colspan="+str(2*quantColunas+1)+">"+indicador+"</th></tr>"
    htmlTit = "<tr><td align=center><b>MÊS</b><td align=center colspan="+str(quantColunas)+"><b>"+lotacao[-5:]+"</b></td>"+\
              "<td align=center colspan="+str(quantColunas)+"><b>"+Constantes.txDepto+"</b></td></tr>"
    htmlCompleto = htmlTh+htmlTit+"<tr><td>&nbsp;</td>"+htmlLabels+htmlLabels+"</tr>"+htmlLinhas
    p(htmlCompleto[:5])
    return htmlCompleto

def substituirTags(texto, idLinha):
    retorno = texto
    for label in todosLabels:
        dado = str(dados.get(idLinha).get(label))
        try:
            dado = float(dado)
            dado = str(dado).rstrip('0').rstrip('.')
        except ValueError:
            pass
        retorno = retorno.replace("<<"+label+">>",dado)
    return retorno

def quebrarLinha(linha):
    array = linha.replace("|ALM","").replace("\"","").split(Constantes.sep)
    return array[:-1]
                       
def moverArquivo(arquivo, pastaOrigem, pastaDestino):
    os.rename(pastaOrigem+arquivo, pastaDestino+gerarNmArq()+"."+arquivo[-3:])    

def gerarNmArq():
    nmArq = datetime.datetime.today().strftime('%Y-%m-%d-%H-%M-%S')
    return nmArq


def imprimeDados():
    for indicador, resps in dados.iteritems():
        p(">>"+indicador)
        for resp, meses in resps.iteritems():
            p(" >>"+resp)
            for mes, infos in meses.iteritems():
                p("  >>"+mes)
                for info, valor in infos.iteritems():
                    p("   >>"+info+": "+valor)


def p(texto):
    if imprimirLog:
        print texto

main()
